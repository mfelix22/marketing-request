<x-app-layout>
    @section('title', 'Pending Approvals')

    <x-slot name="header">
        <h1 class="text-xl font-semibold text-gray-800">Pending Approvals</h1>
        <p class="text-sm text-gray-500 mt-0.5">{{ $requests->total() }} request(s) awaiting your review</p>
    </x-slot>

    <div class="py-4">
        @if ($requests->isEmpty())
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm text-center py-20">
                <svg class="w-14 h-14 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                <p class="text-gray-500 font-medium text-lg">All caught up!</p>
                <p class="text-gray-400 text-sm mt-1">No pending requests to review at this time.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($requests as $req)
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="p-5">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span
                                            class="font-mono text-xs text-gray-400">#{{ str_pad($req->id, 4, '0', STR_PAD_LEFT) }}</span>
                                        <span class="{{ $req->status_badge }}">{{ $req->status_label }}</span>
                                        @if ($req->is_local_campaign)
                                            <span
                                                class="text-xs bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded">Local</span>
                                        @endif
                                        @if ($req->is_group_campaign)
                                            <span
                                                class="text-xs bg-purple-50 text-purple-700 px-2 py-0.5 rounded">Group</span>
                                        @endif
                                    </div>
                                    <h3 class="text-base font-semibold text-gray-800 truncate">{{ $req->purpose }}</h3>
                                    <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            {{ $req->pic_name }}
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                            {{ $req->department?->name ?? 'N/A' }}
                                        </span>
                                        <span
                                            class="flex items-center gap-1 {{ $req->deadline->isPast() ? 'text-red-500 font-medium' : '' }}">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            Deadline: {{ $req->deadline->format('d M Y') }}
                                            @if ($req->deadline->isPast())
                                                (Overdue)
                                            @endif
                                        </span>
                                        <span class="text-gray-400">Submitted
                                            {{ $req->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <a href="{{ route('approvals.show', $req) }}"
                                    class="flex-shrink-0 px-4 py-2 bg-[#1D3557] text-white text-sm font-medium rounded-lg hover:bg-[#162840] transition-colors">
                                    Review
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4">
                {{ $requests->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
