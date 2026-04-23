<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\Department;
use App\Models\MarketingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MarketingRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = MarketingRequest::where('user_id', auth()->id())->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $requests = $query->paginate(15);
        return view('requests.index', compact('requests'));
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();
        return view('requests.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pic_name'               => 'required|string|max:255',
            'department_id'          => 'required|exists:departments,id',
            'date_request'           => 'required|date',
            'deadline'               => 'required|date|after_or_equal:date_request',
            'purpose'                => 'required|string|max:500',
            'detail_concept'         => 'nullable|string|max:2000',
            'further_information'    => 'nullable|string|max:2000',
            'reference_visual'       => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,pdf|max:10240',
            'output_media'           => 'nullable|array',
            'output_media.*'         => 'string',
            'others_description'     => 'nullable|string|max:255',
            'sales_vehicle_type'     => 'nullable|in:passenger_car,commercial_vehicle',
            'aftersales_vehicle_type' => 'nullable|in:passenger_car,commercial_vehicle',
        ]);

        $campaignType = $request->input('campaign_type');

        $data = [
            'user_id'                  => auth()->id(),
            'pic_name'                 => $validated['pic_name'],
            'department_id'            => $validated['department_id'],
            'date_request'             => $validated['date_request'],
            'deadline'                 => $validated['deadline'],
            'is_local_campaign'        => $campaignType === 'lokal',
            'is_group_campaign'        => $campaignType === 'group',
            'for_sales'                => $request->boolean('for_sales'),
            'sales_vehicle_type'       => $request->boolean('for_sales') ? ($validated['sales_vehicle_type'] ?? null) : null,
            'for_aftersales'           => $request->boolean('for_aftersales'),
            'aftersales_vehicle_type'  => $request->boolean('for_aftersales') ? ($validated['aftersales_vehicle_type'] ?? null) : null,
            'for_others'               => $request->boolean('for_others'),
            'others_description'       => $request->boolean('for_others') ? ($validated['others_description'] ?? null) : null,
            'purpose'                  => $validated['purpose'],
            'detail_concept'           => $validated['detail_concept'] ?? null,
            'further_information'      => $validated['further_information'] ?? null,
            'output_media'             => $validated['output_media'] ?? [],
            'status'                   => 'submitted',
        ];

        if ($request->hasFile('reference_visual')) {
            $data['reference_visual'] = $request->file('reference_visual')->store('reference_visuals', 'public');
        }

        $marketingRequest = MarketingRequest::create($data);

        return redirect()->route('requests.show', $marketingRequest)
            ->with('success', 'Your marketing request has been submitted successfully!');
    }

    public function show(MarketingRequest $request)
    {
        // Staff can only view their own requests
        if (auth()->user()->isStaff() && $request->user_id !== auth()->id()) {
            abort(403);
        }

        $request->load(['department', 'user', 'reviewer', 'approvals.approver', 'comments.user']);
        return view('requests.show', compact('request'));
    }

    public function print(MarketingRequest $request)
    {
        if (auth()->user()->isStaff() && $request->user_id !== auth()->id()) {
            abort(403);
        }

        $request->load(['department', 'user', 'reviewer', 'approvals.approver']);
        return view('requests.print', compact('request'));
    }

    public function edit(MarketingRequest $marketingRequest)
    {
        if (!auth()->user()->isStaff() || $marketingRequest->user_id !== auth()->id()) {
            abort(403);
        }

        if ($marketingRequest->status !== 'rejected') {
            abort(403, 'Only rejected requests can be revised.');
        }

        $departments = Department::orderBy('name')->get();
        return view('requests.edit', compact('marketingRequest', 'departments'));
    }

    public function update(Request $request, MarketingRequest $marketingRequest)
    {
        if (!auth()->user()->isStaff() || $marketingRequest->user_id !== auth()->id()) {
            abort(403);
        }

        if ($marketingRequest->status !== 'rejected') {
            abort(403, 'Only rejected requests can be revised.');
        }

        $validated = $request->validate([
            'pic_name'                => 'required|string|max:255',
            'department_id'           => 'required|exists:departments,id',
            'date_request'            => 'required|date',
            'deadline'                => 'required|date|after_or_equal:date_request',
            'purpose'                 => 'required|string|max:500',
            'detail_concept'          => 'nullable|string|max:2000',
            'further_information'     => 'nullable|string|max:2000',
            'reference_visual'        => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,pdf|max:10240',
            'output_media'            => 'nullable|array',
            'output_media.*'          => 'string',
            'others_description'      => 'nullable|string|max:255',
            'sales_vehicle_type'      => 'nullable|in:passenger_car,commercial_vehicle',
            'aftersales_vehicle_type' => 'nullable|in:passenger_car,commercial_vehicle',
        ]);

        $campaignType = $request->input('campaign_type');

        $data = [
            'pic_name'                => $validated['pic_name'],
            'department_id'           => $validated['department_id'],
            'date_request'            => $validated['date_request'],
            'deadline'                => $validated['deadline'],
            'is_local_campaign'       => $campaignType === 'lokal',
            'is_group_campaign'       => $campaignType === 'group',
            'for_sales'               => $request->boolean('for_sales'),
            'sales_vehicle_type'      => $request->boolean('for_sales') ? ($validated['sales_vehicle_type'] ?? null) : null,
            'for_aftersales'          => $request->boolean('for_aftersales'),
            'aftersales_vehicle_type' => $request->boolean('for_aftersales') ? ($validated['aftersales_vehicle_type'] ?? null) : null,
            'for_others'              => $request->boolean('for_others'),
            'others_description'      => $request->boolean('for_others') ? ($validated['others_description'] ?? null) : null,
            'purpose'                 => $validated['purpose'],
            'detail_concept'          => $validated['detail_concept'] ?? null,
            'further_information'     => $validated['further_information'] ?? null,
            'output_media'            => $validated['output_media'] ?? [],
            'status'                  => 'submitted',
            'manager_comment'         => null,
            'reviewed_by'             => null,
            'reviewed_at'             => null,
        ];

        if ($request->hasFile('reference_visual')) {
            if ($marketingRequest->reference_visual) {
                Storage::disk('public')->delete($marketingRequest->reference_visual);
            }
            $data['reference_visual'] = $request->file('reference_visual')->store('reference_visuals', 'public');
        }

        $marketingRequest->update($data);
        $marketingRequest->approvals()->delete();

        return redirect()->route('requests.show', $marketingRequest)
            ->with('success', 'Your request has been revised and resubmitted successfully!');
    }

    public function destroy(MarketingRequest $request)
    {
        abort(404);
    }
}
