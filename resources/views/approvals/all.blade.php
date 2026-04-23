<x-app-layout>
    @section('title', 'All Requests')

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-800">All Requests</h1>
                <p class="text-sm text-gray-500 mt-0.5">{{ $requests->total() }} total requests</p>
            </div>
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
            <select name="department_id"
                class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-300 outline-none bg-white"
                onchange="this.form.submit()">
                <option value="">All Departments</option>
                @foreach ($departments as $dept)
                    <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                        {{ $dept->name }}</option>
                @endforeach
            </select>
        </form>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            @if ($requests->isEmpty())
                <div class="text-center py-16">
                    <p class="text-gray-400 text-sm">No requests found matching your filters.</p>
                </div>
            @else
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                ID</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Requester</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Department</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Purpose</th>
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
                                <td class="px-5 py-3.5 text-gray-800 font-medium">{{ $req->user->name }}</td>
                                <td class="px-5 py-3.5 text-gray-500 text-xs">{{ $req->department?->name ?? '—' }}</td>
                                <td class="px-5 py-3.5 text-gray-700 max-w-xs">{{ Str::limit($req->purpose, 40) }}</td>
                                <td
                                    class="px-5 py-3.5 text-xs {{ $req->deadline->isPast() && $req->status !== 'approved' ? 'text-red-500 font-medium' : 'text-gray-500' }}">
                                    {{ $req->deadline->format('d M Y') }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="{{ $req->status_badge }}">{{ $req->status_label }}</span>
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    @if (in_array($req->status, ['submitted', 'under_review']))
                                        <a href="{{ route('approvals.show', $req) }}"
                                            class="text-blue-600 hover:text-blue-800 text-xs font-medium">Review →</a>
                                    @else
                                        <a href="{{ route('approvals.show', $req) }}"
                                            class="text-gray-400 hover:text-gray-600 text-xs font-medium">View →</a>
                                    @endif
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
