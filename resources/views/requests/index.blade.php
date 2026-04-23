<x-app-layout>
    @section('title', 'My Requests')

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-800">My Requests</h1>
                <p class="text-sm text-gray-500 mt-0.5">All marketing requests you have submitted</p>
            </div>
            <a href="{{ route('requests.create') }}"
                class="inline-flex items-center px-4 py-2 bg-[#1D3557] text-white text-sm font-medium rounded-lg hover:bg-[#162840] transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                New Request
            </a>
        </div>
    </x-slot>

    <div class="py-4">
        <!-- Filters -->
        <form method="GET" class="flex flex-wrap gap-3 mb-5">
            <select name="status"
                class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-300 outline-none bg-white"
                onchange="this.form.submit()">
                <option value="">All Status</option>
                <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Submitted
                </option>
                <option value="under_review" {{ request('status') === 'under_review' ? 'selected' : '' }}>Under Review
                </option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </form>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            @if ($requests->isEmpty())
                <div class="text-center py-16">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-gray-500 font-medium">No requests found</p>
                    <p class="text-gray-400 text-sm mt-1">Submit your first marketing request to get started</p>
                    <a href="{{ route('requests.create') }}"
                        class="mt-4 inline-block px-5 py-2 bg-[#1D3557] text-white text-sm rounded-lg hover:bg-[#162840]">New
                        Request</a>
                </div>
            @else
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                ID</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Purpose</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Campaign</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Date Request</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Deadline</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Status</th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach ($requests as $req)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-3.5 font-mono text-gray-400 text-xs">
                                    #{{ str_pad($req->id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td class="px-5 py-3.5 text-gray-800 font-medium max-w-xs">
                                    {{ Str::limit($req->purpose, 50) }}</td>
                                <td class="px-5 py-3.5 text-gray-500">
                                    @if ($req->is_local_campaign)
                                        <span
                                            class="inline-block text-xs bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded mr-1">Local</span>
                                    @endif
                                    @if ($req->is_group_campaign)
                                        <span
                                            class="inline-block text-xs bg-purple-50 text-purple-700 px-2 py-0.5 rounded">Group</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-gray-500 text-xs">{{ $req->date_request->format('d M Y') }}
                                </td>
                                <td class="px-5 py-3.5 text-gray-500 text-xs">{{ $req->deadline->format('d M Y') }}</td>
                                <td class="px-5 py-3.5">
                                    <span class="{{ $req->status_badge }}">{{ $req->status_label }}</span>
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <a href="{{ route('requests.show', $req) }}"
                                        class="text-blue-600 hover:text-blue-800 text-xs font-medium">View →</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="px-5 py-3 border-t border-gray-100">
                    {{ $requests->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
