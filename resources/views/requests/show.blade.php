<x-app-layout>
    @section('title', 'Request #' . str_pad($request->id, 4, '0', STR_PAD_LEFT))

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('requests.index') }}" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <h1 class="text-xl font-semibold text-gray-800">Request
                        #{{ str_pad($request->id, 4, '0', STR_PAD_LEFT) }}</h1>
                    <span class="{{ $request->status_badge }}">{{ $request->status_label }}</span>
                </div>
                <p class="text-sm text-gray-500 mt-0.5 ml-8">Submitted {{ $request->created_at->diffForHumans() }}</p>
            </div>
            <a href="{{ route('requests.print', $request) }}" target="_blank"
                class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print / PDF
            </a>
            @if ($request->status === 'approved')
                <a href="{{ route('requests.track', $request) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Track Progress
                </a>
            @endif
            @if ($request->status === 'rejected' && $request->user_id === auth()->id())
                <a href="{{ route('requests.edit', $request) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-[#1D3557] text-sm font-medium text-white rounded-lg hover:bg-[#162840] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Revise &amp; Resubmit
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-4">

        @if ($request->status === 'rejected' && $request->manager_comment)
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-5 flex gap-3">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd" />
                </svg>
                <div>
                    <p class="text-sm font-semibold text-red-800">Request Rejected</p>
                    <p class="text-sm text-red-700 mt-0.5">{{ $request->manager_comment }}</p>
                </div>
            </div>
        @endif

        @if ($request->status === 'approved' && $request->manager_comment)
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-5 flex gap-3">
                <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <div>
                    <p class="text-sm font-semibold text-green-800">Request Approved</p>
                    <p class="text-sm text-green-700 mt-0.5">{{ $request->manager_comment }}</p>
                </div>
            </div>
        @endif

        <!-- Info Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-5">
            <!-- Main Info -->
            <div class="lg:col-span-2 bg-white rounded-xl border border-gray-100 shadow-sm">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-sm font-semibold text-[#1D3557] uppercase tracking-wide">Request Details</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">PIC Name</p>
                            <p class="text-sm font-medium text-gray-800">{{ $request->pic_name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Department</p>
                            <p class="text-sm font-medium text-gray-800">{{ $request->department?->name ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Date Request</p>
                            <p class="text-sm font-medium text-gray-800">{{ $request->date_request->format('d F Y') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Deadline</p>
                            <p
                                class="text-sm font-medium text-gray-800 {{ $request->deadline->isPast() && $request->status !== 'approved' ? 'text-red-600' : '' }}">
                                {{ $request->deadline->format('d F Y') }}
                                @if ($request->deadline->isPast() && $request->status !== 'approved')
                                    <span class="text-xs text-red-500">(Overdue)</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="border-t border-gray-50 pt-4">
                        <p class="text-xs text-gray-500 mb-1.5">Project For</p>
                        <div class="flex flex-wrap gap-2">
                            @if ($request->is_local_campaign)
                                <span
                                    class="text-xs bg-indigo-50 text-indigo-700 border border-indigo-100 px-2.5 py-1 rounded-md">Local
                                    Campaign</span>
                            @endif
                            @if ($request->is_group_campaign)
                                <span
                                    class="text-xs bg-purple-50 text-purple-700 border border-purple-100 px-2.5 py-1 rounded-md">Group
                                    Campaign</span>
                            @endif
                            @if ($request->for_sales)
                                <span
                                    class="text-xs bg-blue-50 text-blue-700 border border-blue-100 px-2.5 py-1 rounded-md">Sales
                                    — {{ ucwords(str_replace('_', ' ', $request->sales_vehicle_type ?? '')) }}</span>
                            @endif
                            @if ($request->for_aftersales)
                                <span
                                    class="text-xs bg-teal-50 text-teal-700 border border-teal-100 px-2.5 py-1 rounded-md">Aftersales
                                    —
                                    {{ ucwords(str_replace('_', ' ', $request->aftersales_vehicle_type ?? '')) }}</span>
                            @endif
                            @if ($request->for_others)
                                <span
                                    class="text-xs bg-gray-100 text-gray-700 border border-gray-200 px-2.5 py-1 rounded-md">Others:
                                    {{ $request->others_description }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar: Status & Output -->
            <div class="space-y-4">
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Status</p>
                    <span class="{{ $request->status_badge }} text-sm px-3 py-1.5">{{ $request->status_label }}</span>
                    @if ($request->reviewer)
                        <div class="mt-3 pt-3 border-t border-gray-50">
                            <p class="text-xs text-gray-500">Reviewed by</p>
                            <p class="text-sm font-medium text-gray-700 mt-0.5">{{ $request->reviewer->name }}</p>
                            <p class="text-xs text-gray-400">{{ $request->reviewed_at?->format('d M Y, H:i') }}</p>
                        </div>
                    @endif
                </div>

                @if ($request->output_media && count($request->output_media) > 0)
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Output Media</p>
                        <div class="flex flex-wrap gap-1.5">
                            @php
                                $mediaLabels = [
                                    'poster_a3' => 'Poster A3',
                                    'poster_a4' => 'Poster A4',
                                    'flyer_a5' => 'Flyer A5',
                                    'booklet' => 'Booklet',
                                    'voucher' => 'Voucher',
                                    'x_banner' => 'X-Banner',
                                    'backdrop' => 'Backdrop',
                                    'banner' => 'Banner',
                                    'sticker' => 'Sticker',
                                ];
                            @endphp
                            @foreach ($request->output_media as $media)
                                <span
                                    class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">{{ $mediaLabels[$media] ?? $media }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Project Description -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm mb-5">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-[#1D3557] uppercase tracking-wide">Project Description</h2>
            </div>
            <div class="p-6 space-y-5">
                <div>
                    <p class="text-xs text-gray-500 mb-1.5">Purpose</p>
                    <p class="text-sm text-gray-800">{{ $request->purpose }}</p>
                </div>
                @if ($request->detail_concept)
                    <div class="border-t border-gray-50 pt-4">
                        <p class="text-xs text-gray-500 mb-1.5">Detail Concept</p>
                        <p class="text-sm text-gray-800 whitespace-pre-line">{{ $request->detail_concept }}</p>
                    </div>
                @endif
                @if ($request->further_information)
                    <div class="border-t border-gray-50 pt-4">
                        <p class="text-xs text-gray-500 mb-1.5">Further Information</p>
                        <p class="text-sm text-gray-800 whitespace-pre-line">{{ $request->further_information }}</p>
                    </div>
                @endif
                @if ($request->reference_visual)
                    <div class="border-t border-gray-50 pt-4">
                        <p class="text-xs text-gray-500 mb-2">Reference Visual</p>
                        @php
                            $refExt = strtolower(pathinfo($request->reference_visual, PATHINFO_EXTENSION));
                            $refFilename =
                                'REQ-' . str_pad($request->id, 4, '0', STR_PAD_LEFT) . '-reference.' . $refExt;
                        @endphp
                        <a href="{{ Storage::url($request->reference_visual) }}" target="_blank"
                            download="{{ $refFilename }}"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Download Reference Visual
                            <span class="text-xs text-gray-400">({{ strtoupper($refExt) }})</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Approval History -->
        @if ($request->approvals->isNotEmpty())
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-sm font-semibold text-[#1D3557] uppercase tracking-wide">Approval History</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @foreach ($request->approvals as $approval)
                            <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                                <div
                                    class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 {{ $approval->status === 'approved' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                    @if ($approval->status === 'approved')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-gray-800">{{ $approval->approver->name }}
                                        </p>
                                        <p class="text-xs text-gray-400">
                                            {{ $approval->acted_at->format('d M Y, H:i') }}</p>
                                    </div>
                                    <p class="text-xs text-gray-500 capitalize mt-0.5">{{ $approval->status }}</p>
                                    @if ($approval->comment)
                                        <p class="text-sm text-gray-600 mt-1 italic">"{{ $approval->comment }}"</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
        <!-- Approval Signature Section -->
        @if ($request->is_local_campaign || $request->is_group_campaign)
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm mt-5">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-sm font-semibold text-[#1D3557] uppercase tracking-wide">
                        {{ $request->is_local_campaign ? 'Lokal Campaign Approval' : 'Group Campaign Approval' }}
                    </h2>
                </div>
                <div class="p-6">
                    @php
                        $approvalsList = $request->approvals->sortBy('acted_at')->values();
                    @endphp
                    @if ($request->is_local_campaign)
                        <table class="w-full border border-gray-300 text-sm">
                            <thead>
                                <tr>
                                    <th
                                        class="border border-gray-300 px-4 py-2 text-center text-gray-700 font-semibold w-1/3">
                                        Request By</th>
                                    <th
                                        class="border border-gray-300 px-4 py-2 text-center text-gray-700 font-semibold w-1/3">
                                        Approve By<br><span class="text-xs font-normal text-gray-500">Sales /
                                            Aftersales
                                            Manager</span></th>
                                    <th
                                        class="border border-gray-300 px-4 py-2 text-center text-gray-700 font-semibold w-1/3">
                                        Acknowledged By<br><span class="text-xs font-normal text-gray-500">Branch
                                            Manager / GM</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border border-gray-300 h-24 align-bottom px-4 py-3">
                                        <div class="space-y-1.5">
                                            <div class="flex items-end gap-1"><span
                                                    class="text-xs text-gray-600 whitespace-nowrap">Name :</span>
                                                <div
                                                    class="flex-1 border-b border-gray-400 pb-0.5 text-xs text-gray-800 font-medium">
                                                    {{ $request->user->name }}</div>
                                            </div>
                                            <div class="flex items-end gap-1"><span
                                                    class="text-xs text-gray-600 whitespace-nowrap">Date :</span>
                                                <div
                                                    class="flex-1 border-b border-gray-400 pb-0.5 text-xs text-gray-600">
                                                    {{ $request->created_at->format('d M Y') }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="border border-gray-300 h-24 align-bottom px-4 py-3">
                                        @php $a1 = $approvalsList->get(0); @endphp
                                        <div class="space-y-1.5">
                                            <div class="flex items-end gap-1"><span
                                                    class="text-xs text-gray-600 whitespace-nowrap">Name :</span>
                                                <div
                                                    class="flex-1 border-b border-gray-400 pb-0.5 text-xs {{ $a1 ? 'text-gray-800 font-medium' : '' }}">
                                                    {{ $a1?->approver->name ?? '' }}</div>
                                            </div>
                                            <div class="flex items-end gap-1"><span
                                                    class="text-xs text-gray-600 whitespace-nowrap">Date :</span>
                                                <div
                                                    class="flex-1 border-b border-gray-400 pb-0.5 text-xs text-gray-600">
                                                    {{ $a1?->acted_at?->format('d M Y') ?? '' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="border border-gray-300 h-24 align-bottom px-4 py-3">
                                        @php $a2 = $approvalsList->get(1); @endphp
                                        <div class="space-y-1.5">
                                            <div class="flex items-end gap-1"><span
                                                    class="text-xs text-gray-600 whitespace-nowrap">Name :</span>
                                                <div
                                                    class="flex-1 border-b border-gray-400 pb-0.5 text-xs {{ $a2 ? 'text-gray-800 font-medium' : '' }}">
                                                    {{ $a2?->approver->name ?? '' }}</div>
                                            </div>
                                            <div class="flex items-end gap-1"><span
                                                    class="text-xs text-gray-600 whitespace-nowrap">Date :</span>
                                                <div
                                                    class="flex-1 border-b border-gray-400 pb-0.5 text-xs text-gray-600">
                                                    {{ $a2?->acted_at?->format('d M Y') ?? '' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    @else
                        <table class="w-full border border-gray-300 text-sm">
                            <thead>
                                <tr>
                                    <th
                                        class="border border-gray-300 px-4 py-2 text-center text-gray-700 font-semibold w-1/3">
                                        Request By<br><span class="text-xs font-normal text-gray-500">Marketing
                                            Corporate</span></th>
                                    <th
                                        class="border border-gray-300 px-4 py-2 text-center text-gray-700 font-semibold w-1/3">
                                        Approve By<br><span class="text-xs font-normal text-gray-500">Aftersales
                                            Manager</span></th>
                                    <th
                                        class="border border-gray-300 px-4 py-2 text-center text-gray-700 font-semibold w-1/3">
                                        Acknowledged By<br><span class="text-xs font-normal text-gray-500">Albert
                                            (Director)</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    {{-- Request By: auto-filled with creator --}}
                                    <td class="border border-gray-300 h-24 align-bottom px-4 py-3">
                                        <div class="space-y-1.5">
                                            <div class="flex items-end gap-1"><span
                                                    class="text-xs text-gray-600 whitespace-nowrap">Name :</span>
                                                <div
                                                    class="flex-1 border-b border-gray-400 pb-0.5 text-xs text-gray-800 font-medium">
                                                    {{ $request->user->name }}</div>
                                            </div>
                                            <div class="flex items-end gap-1"><span
                                                    class="text-xs text-gray-600 whitespace-nowrap">Date :</span>
                                                <div
                                                    class="flex-1 border-b border-gray-400 pb-0.5 text-xs text-gray-600">
                                                    {{ $request->created_at->format('d M Y') }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    {{-- Approve By: first approver record --}}
                                    @foreach ([0, 1] as $i)
                                        @php $ap = $approvalsList->get($i); @endphp
                                        <td class="border border-gray-300 h-24 align-bottom px-4 py-3">
                                            <div class="space-y-1.5">
                                                <div class="flex items-end gap-1"><span
                                                        class="text-xs text-gray-600 whitespace-nowrap">Name :</span>
                                                    <div
                                                        class="flex-1 border-b border-gray-400 pb-0.5 text-xs {{ $ap ? 'text-gray-800 font-medium' : '' }}">
                                                        {{ $ap?->approver->name ?? '' }}</div>
                                                </div>
                                                <div class="flex items-end gap-1"><span
                                                        class="text-xs text-gray-600 whitespace-nowrap">Date :</span>
                                                    <div
                                                        class="flex-1 border-b border-gray-400 pb-0.5 text-xs text-gray-600">
                                                        {{ $ap?->acted_at?->format('d M Y') ?? '' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        @endif

        <!-- Comments / Notes Thread -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm mt-5">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-[#1D3557] uppercase tracking-wide">
                    Comments &amp; Notes
                    <span
                        class="ml-1 text-xs font-normal text-gray-400 normal-case">({{ $request->comments->count() }})</span>
                </h2>
            </div>
            <div class="p-6">
                {{-- Existing comments --}}
                @if ($request->comments->isEmpty())
                    <p class="text-sm text-gray-400 text-center py-4">No comments yet. Be the first to add a note.</p>
                @else
                    <div class="space-y-4 mb-6">
                        @foreach ($request->comments as $comment)
                            <div class="flex gap-3">
                                <div
                                    class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center">
                                    <span
                                        class="text-white text-xs font-semibold">{{ substr($comment->user->name, 0, 1) }}</span>
                                </div>
                                <div class="flex-1 bg-gray-50 rounded-lg px-4 py-3">
                                    <div class="flex items-center justify-between mb-1">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="text-sm font-medium text-gray-800">{{ $comment->user->name }}</span>
                                            <span
                                                class="text-xs text-gray-400 capitalize bg-gray-100 px-1.5 py-0.5 rounded">{{ $comment->user->role->value }}</span>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <span
                                                class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                            @if (auth()->id() === $comment->user_id || auth()->user()->canViewAllRequests())
                                                <form method="POST"
                                                    action="{{ route('comments.destroy', $comment) }}"
                                                    onsubmit="return confirm('Delete this comment?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-gray-300 hover:text-red-400 transition-colors">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-700 whitespace-pre-line">{{ $comment->body }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Add comment form --}}
                @if ($request->user_id === auth()->id() || auth()->user()->canViewAllRequests())
                    <form method="POST" action="{{ route('comments.store', $request) }}"
                        class="flex gap-3 items-start">
                        @csrf
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center">
                            <span
                                class="text-white text-xs font-semibold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                        <div class="flex-1">
                            <textarea name="body" rows="2" placeholder="Write a comment or note..."
                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-400 outline-none resize-none transition @error('body') border-red-400 @enderror">{{ old('body') }}</textarea>
                            @error('body')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <div class="flex justify-end mt-2">
                                <button type="submit"
                                    class="px-4 py-1.5 bg-[#1D3557] text-white text-xs font-semibold rounded-lg hover:bg-[#162840] transition-colors">
                                    Post Comment
                                </button>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
