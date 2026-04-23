<x-app-layout>
    @section('title', 'Dashboard')

    <x-slot name="header">
        <h1 class="text-xl font-semibold text-gray-800">Dashboard</h1>
        <p class="text-sm text-gray-500 mt-0.5">Welcome back, {{ auth()->user()->name }}</p>
    </x-slot>

    @php
        $user = auth()->user();
        $myRequests = \App\Models\MarketingRequest::where('user_id', $user->id);
        $totalMine = (clone $myRequests)->count();
        $pendingMine = (clone $myRequests)->whereIn('status', ['submitted', 'under_review'])->count();
        $approvedMine = (clone $myRequests)->where('status', 'approved')->count();
        $rejectedMine = (clone $myRequests)->where('status', 'rejected')->count();
        $recentMine = (clone $myRequests)->latest()->take(5)->get();

        if ($user->canApprove()) {
            $pendingAll = \App\Models\MarketingRequest::whereIn('status', ['submitted', 'under_review'])->count();
            $totalAll = \App\Models\MarketingRequest::count();
        }
    @endphp

    <div class="py-4">
        <!-- Stats -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">My Requests</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalMine }}</p>
                <p class="text-xs text-gray-400 mt-1">All time</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Pending</p>
                <p class="text-3xl font-bold text-yellow-500 mt-1">{{ $pendingMine }}</p>
                <p class="text-xs text-gray-400 mt-1">Awaiting review</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Approved</p>
                <p class="text-3xl font-bold text-green-500 mt-1">{{ $approvedMine }}</p>
                <p class="text-xs text-gray-400 mt-1">Completed</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Rejected</p>
                <p class="text-3xl font-bold text-red-500 mt-1">{{ $rejectedMine }}</p>
                <p class="text-xs text-gray-400 mt-1">Needs revision</p>
            </div>
        </div>

        @if ($user->canApprove())
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
                <div class="bg-gradient-to-r from-[#1D3557] to-blue-700 rounded-xl p-5 text-white">
                    <p class="text-sm font-medium text-blue-200">Pending Approvals</p>
                    <p class="text-4xl font-bold mt-1">{{ $pendingAll }}</p>
                    <a href="{{ route('approvals.index') }}"
                        class="inline-flex items-center mt-3 text-sm text-blue-200 hover:text-white transition-colors">
                        Review now
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Requests (All)</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalAll }}</p>
                    <a href="{{ route('approvals.all') }}"
                        class="inline-flex items-center mt-3 text-sm text-blue-600 hover:text-blue-800 transition-colors">
                        View all
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        @endif

        <!-- Quick Action -->
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-semibold text-gray-700">My Recent Requests</h2>
            <a href="{{ route('requests.create') }}"
                class="inline-flex items-center px-4 py-2 bg-[#1D3557] text-white text-sm font-medium rounded-lg hover:bg-[#162840] transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                New Request
            </a>
        </div>

        <!-- Recent Requests Table -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            @if ($recentMine->isEmpty())
                <div class="text-center py-16">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-gray-400 text-sm">No requests yet</p>
                    <a href="{{ route('requests.create') }}"
                        class="mt-3 inline-block text-sm text-blue-600 hover:underline">Submit your first request</a>
                </div>
            @else
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Request ID</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Purpose</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Deadline</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Status</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach ($recentMine as $req)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 font-mono text-gray-500 text-xs">
                                    #{{ str_pad($req->id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td class="px-4 py-3 text-gray-800 font-medium">{{ Str::limit($req->purpose, 45) }}
                                </td>
                                <td class="px-4 py-3 text-gray-500">{{ $req->deadline->format('d M Y') }}</td>
                                <td class="px-4 py-3">
                                    <span class="{{ $req->status_badge }}">{{ $req->status_label }}</span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('requests.show', $req) }}"
                                        class="text-blue-600 hover:text-blue-800 text-xs font-medium">View →</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if ($totalMine > 5)
                    <div class="px-4 py-3 border-t border-gray-100 text-right">
                        <a href="{{ route('requests.index') }}" class="text-sm text-blue-600 hover:underline">View all
                            {{ $totalMine }} requests →</a>
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-app-layout>
