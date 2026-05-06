<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\CalendarEvent;
use App\Models\MarketingRequest;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    private function authorizeAccess(): void
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        if (!in_array($user->role, [Role::Admin, Role::Marcom])) {
            abort(403);
        }
    }

    /** Main calendar page */
    public function index()
    {
        $this->authorizeAccess();

        $requests = MarketingRequest::where('status', 'approved')
            ->orderBy('purpose')
            ->get(['id', 'purpose']);

        $categories = CalendarEvent::categoryLabels();
        $colors     = CalendarEvent::categoryColors();

        return view('calendar.index', compact('requests', 'categories', 'colors'));
    }

    /** JSON feed for FullCalendar */
    public function events(Request $request)
    {
        $this->authorizeAccess();

        $events = CalendarEvent::where('status', 'approved')
            ->with('creator')
            ->get();

        return response()->json($events->map(function (CalendarEvent $e) {
            return [
                'id'                  => $e->id,
                'title'               => $e->title,
                'start'               => $e->all_day
                    ? $e->start_datetime->toDateString()
                    : $e->start_datetime->toIso8601String(),
                'end'                 => $e->end_datetime
                    ? ($e->all_day
                        ? $e->end_datetime->addDay()->toDateString() // FullCalendar end is exclusive
                        : $e->end_datetime->toIso8601String())
                    : null,
                'allDay'              => $e->all_day,
                'color'               => $e->color,
                'extendedProps'       => [
                    'description'           => $e->description,
                    'category'              => $e->category,
                    'created_by'            => $e->creator?->name,
                    'marketing_request_id'  => $e->marketing_request_id,
                    'google_start'          => $e->all_day
                        ? $e->start_datetime->format('Ymd')
                        : $e->start_datetime->format('Ymd\THis\Z'),
                    'google_end'            => $e->end_datetime
                        ? ($e->all_day
                            ? $e->end_datetime->addDay()->format('Ymd')
                            : $e->end_datetime->format('Ymd\THis\Z'))
                        : null,
                ],
            ];
        }));
    }

    /** Store a new event (Marcom/Admin) */
    public function store(Request $request)
    {
        $this->authorizeAccess();

        $validated = $request->validate([
            'title'                => ['required', 'string', 'max:200'],
            'description'          => ['nullable', 'string', 'max:2000'],
            'category'             => ['required', 'in:campaign,design,deadline,meeting,other'],
            'start_datetime'       => ['required', 'date'],
            'end_datetime'         => ['nullable', 'date', 'after_or_equal:start_datetime'],
            'all_day'              => ['boolean'],
            'marketing_request_id' => ['nullable', 'exists:marketing_requests,id'],
        ]);

        $colors = CalendarEvent::categoryColors();

        CalendarEvent::create([
            ...$validated,
            'color'      => $colors[$validated['category']],
            'created_by' => auth()->id(),
            'status'     => 'pending_manager',
        ]);

        return redirect()->route('calendar.index')
            ->with('success', 'Event submitted for approval. A Manager needs to approve it first.');
    }

    /** Update an existing event (only if rejected — allows resubmit) */
    public function update(Request $request, CalendarEvent $calendarEvent)
    {
        $this->authorizeAccess();

        if ($calendarEvent->created_by !== auth()->id()) {
            abort(403);
        }

        if ($calendarEvent->status !== 'rejected') {
            return back()->with('error', 'Only rejected events can be edited and resubmitted.');
        }

        $validated = $request->validate([
            'title'                => ['required', 'string', 'max:200'],
            'description'          => ['nullable', 'string', 'max:2000'],
            'category'             => ['required', 'in:campaign,design,deadline,meeting,other'],
            'start_datetime'       => ['required', 'date'],
            'end_datetime'         => ['nullable', 'date', 'after_or_equal:start_datetime'],
            'all_day'              => ['boolean'],
            'marketing_request_id' => ['nullable', 'exists:marketing_requests,id'],
        ]);

        $colors = CalendarEvent::categoryColors();

        $calendarEvent->update([
            ...$validated,
            'color'            => $colors[$validated['category']],
            'status'           => 'pending_manager',
            'rejection_reason' => null,
        ]);

        // Clear previous approvals so the flow restarts
        $calendarEvent->approvals()->delete();

        return redirect()->route('calendar.index')
            ->with('success', 'Event resubmitted for approval.');
    }

    /** Delete an event */
    public function destroy(CalendarEvent $calendarEvent)
    {
        $this->authorizeAccess();

        if ($calendarEvent->created_by !== auth()->id() && !in_array(auth()->user()->role, [Role::Admin])) {
            abort(403);
        }

        $calendarEvent->delete();

        return back()->with('success', 'Event deleted.');
    }

    /** Pending events list — for Marcom to see status of their submissions */
    public function pending()
    {
        $this->authorizeAccess();

        $events = CalendarEvent::where('created_by', auth()->id())
            ->whereIn('status', ['pending_manager', 'pending_gm_director', 'rejected'])
            ->with('approvals.approver')
            ->latest()
            ->get();

        return view('calendar.pending', compact('events'));
    }
}
