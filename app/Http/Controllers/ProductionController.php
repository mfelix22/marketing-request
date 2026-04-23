<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\MarketingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductionController extends Controller
{
    /**
     * List all approved requests — the production queue.
     * Only admin/marcom roles can access this.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if (!in_array($user->role, [Role::Admin, Role::Marcom])) {
            abort(403);
        }

        $requests = MarketingRequest::where('status', 'approved')
            ->with(['user', 'department'])
            ->orderByRaw("FIELD(production_status, 'revision', 'on_process', 'pending', 'completed')")
            ->latest('production_updated_at')
            ->get();

        // Counts for summary badges
        $counts = [
            'pending'    => $requests->where('production_status', 'pending')->count(),
            'on_process' => $requests->where('production_status', 'on_process')->count(),
            'revision'   => $requests->where('production_status', 'revision')->count(),
            'completed'  => $requests->where('production_status', 'completed')->count(),
        ];

        return view('production.index', compact('requests', 'counts'));
    }

    /**
     * Advance the production status of an approved request.
     * Only admin/marcom roles can do this.
     *
     * Guided flow (each stage unlocks the next button):
     *   approved → [Start Production]  → on_process
     *   on_process → [Send for Revision] OR [Mark Completed]
     *   revision   → [Resume Production]  → on_process
     *   on_process → [Mark Completed]     → completed (requires file upload)
     */
    public function update(Request $request, MarketingRequest $marketingRequest)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if (!in_array($user->role, [Role::Admin, Role::Marcom])) {
            abort(403);
        }

        if ($marketingRequest->status !== 'approved') {
            return back()->with('error', 'Production can only be managed for fully approved requests.');
        }

        $action = $request->input('action');

        switch ($action) {
            case 'start':
                // pending → on_process
                if ($marketingRequest->production_status !== 'pending') {
                    return back()->with('error', 'Invalid action for current production status.');
                }
                $marketingRequest->production_status     = 'on_process';
                $marketingRequest->production_updated_at = now();
                $marketingRequest->save();

                return back()->with('success', 'Production started — status set to On Process.');

            case 'revision':
                // on_process → revision (requires a note)
                if ($marketingRequest->production_status !== 'on_process') {
                    return back()->with('error', 'Invalid action for current production status.');
                }

                $validated = $request->validate([
                    'production_notes' => ['required', 'string', 'max:1000'],
                ]);

                $marketingRequest->production_status     = 'revision';
                $marketingRequest->production_notes      = $validated['production_notes'];
                $marketingRequest->production_updated_at = now();
                $marketingRequest->save();

                return back()->with('success', 'Request sent back for revision. The requestor has been notified.');

            case 'resume':
                // revision → on_process
                if ($marketingRequest->production_status !== 'revision') {
                    return back()->with('error', 'Invalid action for current production status.');
                }
                $marketingRequest->production_status     = 'on_process';
                $marketingRequest->production_notes      = null;
                $marketingRequest->production_updated_at = now();
                $marketingRequest->save();

                return back()->with('success', 'Production resumed — status set to On Process.');

            case 'complete':
                // on_process → completed (requires file upload)
                if ($marketingRequest->production_status !== 'on_process') {
                    return back()->with('error', 'Invalid action for current production status.');
                }

                $validated = $request->validate([
                    'final_file' => ['required', 'file', 'max:20480'], // 20 MB
                ]);

                $path = $request->file('final_file')->store('final_files', 'public');

                // Remove old final file if it exists
                if ($marketingRequest->final_file) {
                    Storage::disk('public')->delete($marketingRequest->final_file);
                }

                $marketingRequest->production_status     = 'completed';
                $marketingRequest->final_file            = $path;
                $marketingRequest->production_updated_at = now();
                $marketingRequest->save();

                return back()->with('success', 'Request marked as Completed and final file uploaded.');

            default:
                return back()->with('error', 'Unknown action.');
        }
    }

    /**
     * Completed requests — visible to all authenticated users.
     * Staff see only their own; everyone else sees all.
     */
    public function completed()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $query = MarketingRequest::where('production_status', 'completed')
            ->with(['user', 'department']);

        if (!$user->canViewAllRequests()) {
            $query->where('user_id', $user->id);
        }

        $requests = $query->orderByDesc('production_updated_at')->paginate(20);

        return view('production.completed', compact('requests'));
    }

    /**
     * User-facing tracking page — shows production progress for the request.
     */
    public function track(MarketingRequest $marketingRequest)
    {
        $user = auth()->user();

        // Only the owner or approvers may view the tracking page
        if ($marketingRequest->user_id !== $user->id && !$user->canViewAllRequests()) {
            abort(403);
        }

        if ($marketingRequest->status !== 'approved') {
            return redirect()->route('requests.show', $marketingRequest)
                ->with('error', 'Tracking is only available for approved requests.');
        }

        $marketingRequest->load(['user', 'department', 'approvals.approver', 'comments.user']);

        return view('requests.track', compact('marketingRequest'));
    }
}
