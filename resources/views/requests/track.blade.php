<x-app-layout>
    @section('title', 'Track Request #' . str_pad($marketingRequest->id, 4, '0', STR_PAD_LEFT))

    @php
        $req = $marketingRequest;
        $prodStages = [
            ['key' => 'pending', 'label' => 'Pending', 'desc' => 'Request received, waiting to start production.'],
            ['key' => 'on_process', 'label' => 'On Process', 'desc' => 'Admin Marketing is working on this request.'],
            ['key' => 'revision', 'label' => 'Revision', 'desc' => 'Revision requested — see notes below.'],
            ['key' => 'completed', 'label' => 'Completed', 'desc' => 'Production complete. Final file is ready.'],
        ];
        $prodOrder = array_flip(array_column($prodStages, 'key'));
        $currentIdx = $prodOrder[$req->production_status] ?? 0;
    @endphp

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('requests.show', $req) }}" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-xl font-semibold text-gray-800">
                        Production Tracking — Request #{{ str_pad($req->id, 4, '0', STR_PAD_LEFT) }}
                    </h1>
                    <p class="text-sm text-gray-500 mt-0.5">Submitted {{ $req->created_at->diffForHumans() }} by
                        {{ $req->user->name }}</p>
                </div>
            </div>
            <span
                class="{{ $req->production_status_badge }} text-sm px-3 py-1.5">{{ $req->production_status_label }}</span>
        </div>
    </x-slot>

    <div class="py-4">

        {{-- Flash messages --}}
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-5 flex gap-3">
                <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <p class="text-sm text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        {{-- === PROGRESS STEPPER === --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm mb-5">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-[#1D3557] uppercase tracking-wide">Production Progress</h2>
            </div>
            <div class="p-6">
                {{-- Horizontal stepper --}}
                <div class="flex items-start mb-6">
                    @foreach ($prodStages as $i => $stage)
                        @php
                            $isDone = $i < $currentIdx;
                            $isActive = $i === $currentIdx;
                        @endphp
                        <div class="flex items-start {{ $i < count($prodStages) - 1 ? 'flex-1' : '' }}">
                            <div class="flex flex-col items-center">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold border-2 transition-all
                                    {{ $isDone
                                        ? 'border-green-500 text-white'
                                        : ($isActive
                                            ? 'bg-[#1D3557] border-[#1D3557] text-white ring-4 ring-[#1D3557]/10'
                                            : 'bg-white border-gray-200 text-gray-400') }}"
                                    @if ($isDone) style="background-color:#22c55e" @endif>
                                    @if ($isDone)
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    @elseif ($isActive && $stage['key'] === 'revision')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @elseif ($isActive && $stage['key'] === 'completed')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    @else
                                        <span class="text-sm">{{ $i + 1 }}</span>
                                    @endif
                                </div>
                                <span
                                    class="text-xs mt-2 text-center font-medium whitespace-nowrap
                                    {{ $isActive ? 'text-[#1D3557]' : ($isDone ? 'text-green-600' : 'text-gray-400') }}">
                                    {{ $stage['label'] }}
                                </span>
                                @if ($isActive)
                                    <span
                                        class="text-xs mt-0.5 text-center text-gray-500 max-w-[80px] text-center leading-tight hidden sm:block">
                                        {{ $stage['desc'] }}
                                    </span>
                                @endif
                            </div>
                            @if ($i < count($prodStages) - 1)
                                <div class="flex-1 h-0.5 mx-3 mt-5 transition-all {{ $i < $currentIdx ? '' : 'bg-gray-200' }}"
                                    @if ($i < $currentIdx) style="background-color:#4ade80" @endif>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                {{-- Current status description (mobile) --}}
                <div class="sm:hidden text-sm text-gray-600 bg-gray-50 rounded-lg px-4 py-3 mb-4">
                    {{ $prodStages[$currentIdx]['desc'] }}
                </div>

                {{-- Last updated --}}
                @if ($req->production_updated_at)
                    <p class="text-xs text-gray-400 text-center">
                        Last updated {{ $req->production_updated_at->diffForHumans() }}
                        ({{ $req->production_updated_at->format('d M Y, H:i') }})
                    </p>
                @endif
            </div>
        </div>

        {{-- === REVISION NOTES === --}}
        @if ($req->production_status === 'revision' && $req->production_notes)
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-5 mb-5 flex gap-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-amber-900">Revision Requested by Admin Marketing</p>
                    <p class="text-sm text-amber-800 mt-1">{{ $req->production_notes }}</p>
                    <p class="text-xs text-amber-600 mt-2">Updated {{ $req->production_updated_at?->diffForHumans() }}
                    </p>
                </div>
            </div>
        @endif

        {{-- === FINAL FILE DOWNLOAD === --}}
        @if ($req->production_status === 'completed')
            <div
                class="bg-green-50 border border-green-200 rounded-xl p-5 mb-5 flex items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-green-900">Production Completed!</p>
                        <p class="text-sm text-green-700 mt-0.5">Your final file is ready to download.</p>
                    </div>
                </div>
                @if ($req->final_file)
                    <a href="{{ Storage::url($req->final_file) }}" target="_blank"
                        class="flex-shrink-0 inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download Final File
                    </a>
                @endif
            </div>
        @endif

        {{-- === BASIC INFO === --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm mb-5">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-[#1D3557] uppercase tracking-wide">Request Info</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">PIC Name</p>
                        <p class="text-sm font-medium text-gray-800">{{ $req->pic_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Department</p>
                        <p class="text-sm font-medium text-gray-800">{{ $req->department?->name ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Date Request</p>
                        <p class="text-sm font-medium text-gray-800">{{ $req->date_request->format('d F Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Deadline</p>
                        <p class="text-sm font-medium text-gray-800">{{ $req->deadline->format('d F Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Submitted By</p>
                        <p class="text-sm font-medium text-gray-800">{{ $req->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Approval Status</p>
                        <span class="{{ $req->status_badge }} text-xs px-2.5 py-1">{{ $req->status_label }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- === PROJECT DETAIL === --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm mb-5">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-[#1D3557] uppercase tracking-wide">Project Detail</h2>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <p class="text-xs text-gray-500 mb-1.5">Purpose</p>
                    <p class="text-sm text-gray-800">{{ $req->purpose }}</p>
                </div>
                @if ($req->detail_concept)
                    <div class="border-t border-gray-50 pt-4">
                        <p class="text-xs text-gray-500 mb-1.5">Detail Concept</p>
                        <p class="text-sm text-gray-800 whitespace-pre-line">{{ $req->detail_concept }}</p>
                    </div>
                @endif
                @if ($req->output_media && count($req->output_media) > 0)
                    <div class="border-t border-gray-50 pt-4">
                        <p class="text-xs text-gray-500 mb-1.5">Output Media</p>
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
                        <div class="flex flex-wrap gap-1.5">
                            @foreach ($req->output_media as $media)
                                <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">
                                    {{ $mediaLabels[$media] ?? $media }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- === UPLOADED FILES === --}}
        @if ($req->reference_visual || $req->final_file)
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm mb-5">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-sm font-semibold text-[#1D3557] uppercase tracking-wide">Uploaded Files</h2>
                </div>
                <div class="p-6 space-y-3">
                    @if ($req->reference_visual)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">Reference Visual</p>
                                    <p class="text-xs text-gray-500">Submitted with request</p>
                                </div>
                            </div>
                            <a href="{{ Storage::url($req->reference_visual) }}" target="_blank"
                                class="text-sm text-blue-600 hover:text-blue-700 font-medium">View</a>
                        </div>
                    @endif
                    @if ($req->final_file)
                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">Final Deliverable</p>
                                    <p class="text-xs text-gray-500">Uploaded by Admin Marketing</p>
                                </div>
                            </div>
                            <a href="{{ Storage::url($req->final_file) }}" target="_blank"
                                class="text-sm text-green-600 hover:text-green-700 font-medium">Download</a>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- Footer link --}}
        <div class="text-center">
            <a href="{{ route('requests.show', $req) }}" class="text-sm text-gray-500 hover:text-gray-700 underline">
                View full request details &rarr;
            </a>
        </div>

    </div>
</x-app-layout>
