<x-app-layout>
    @section('title', 'Revise Request #' . str_pad($marketingRequest->id, 4, '0', STR_PAD_LEFT))

    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('requests.show', $marketingRequest) }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-xl font-semibold text-gray-800">Revise Request
                    #{{ str_pad($marketingRequest->id, 4, '0', STR_PAD_LEFT) }}</h1>
                <p class="text-sm text-gray-500 mt-0.5">Update the details below and resubmit for review.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-4">

        {{-- Rejection reason notice --}}
        @if ($marketingRequest->manager_comment)
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-5 flex gap-3">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd" />
                </svg>
                <div>
                    <p class="text-sm font-semibold text-red-800">Rejection Reason</p>
                    <p class="text-sm text-red-700 mt-0.5">{{ $marketingRequest->manager_comment }}</p>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('requests.update', $marketingRequest) }}" enctype="multipart/form-data"
            x-data="requestForm()">
            @csrf
            @method('PUT')

            <!-- Card: Basic Info -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm mb-5">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-sm font-semibold text-[#1D3557] uppercase tracking-wide">Request Information</h2>
                </div>
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- PIC Name -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">PIC Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="pic_name" value="{{ old('pic_name', $marketingRequest->pic_name) }}"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-400 outline-none transition @error('pic_name') border-red-400 @enderror">
                        @error('pic_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Department -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Department <span
                                class="text-red-500">*</span></label>
                        <select name="department_id"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-400 outline-none transition @error('department_id') border-red-400 @enderror">
                            <option value="">Select Department</option>
                            @foreach ($departments as $dept)
                                <option value="{{ $dept->id }}"
                                    {{ old('department_id', $marketingRequest->department_id) == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('department_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date Request -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Date Request <span
                                class="text-red-500">*</span></label>
                        <input type="date" name="date_request"
                            value="{{ old('date_request', $marketingRequest->date_request->format('Y-m-d')) }}"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-400 outline-none transition @error('date_request') border-red-400 @enderror">
                        @error('date_request')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deadline -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Deadline <span
                                class="text-red-500">*</span></label>
                        <input type="date" name="deadline"
                            value="{{ old('deadline', $marketingRequest->deadline->format('Y-m-d')) }}"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-400 outline-none transition @error('deadline') border-red-400 @enderror">
                        @error('deadline')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Card: Project For -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm mb-5">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-sm font-semibold text-[#1D3557] uppercase tracking-wide">Project For</h2>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Campaign Type -->
                    <div class="flex flex-wrap gap-6">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="campaign_type" value="lokal" x-model="campaignType"
                                {{ old('campaign_type', $marketingRequest->is_local_campaign ? 'lokal' : '') === 'lokal' ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-300">
                            <span class="text-sm text-gray-700 font-medium">Lokal Campaign</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="campaign_type" value="group" x-model="campaignType"
                                {{ old('campaign_type', $marketingRequest->is_group_campaign ? 'group' : '') === 'group' ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-300">
                            <span class="text-sm text-gray-700 font-medium">Group Campaign</span>
                        </label>
                    </div>

                    <div class="border-t border-gray-50 pt-4 space-y-3">
                        <!-- Sales -->
                        <div class="flex flex-wrap items-center gap-4">
                            <label class="flex items-center gap-2 w-28 cursor-pointer">
                                <input type="checkbox" name="for_sales" value="1" x-model="forSales"
                                    {{ old('for_sales', $marketingRequest->for_sales) ? 'checked' : '' }}
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-300">
                                <span class="text-sm text-gray-700">Sales</span>
                            </label>
                            <div class="flex gap-4" x-show="forSales" x-cloak x-transition>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="sales_vehicle_type" value="passenger_car"
                                        {{ old('sales_vehicle_type', $marketingRequest->sales_vehicle_type) === 'passenger_car' ? 'checked' : '' }}
                                        class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-300">
                                    <span class="text-sm text-gray-600">Passenger Car</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="sales_vehicle_type" value="commercial_vehicle"
                                        {{ old('sales_vehicle_type', $marketingRequest->sales_vehicle_type) === 'commercial_vehicle' ? 'checked' : '' }}
                                        class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-300">
                                    <span class="text-sm text-gray-600">Commercial Vehicle</span>
                                </label>
                            </div>
                        </div>

                        <!-- Aftersales -->
                        <div class="flex flex-wrap items-center gap-4">
                            <label class="flex items-center gap-2 w-28 cursor-pointer">
                                <input type="checkbox" name="for_aftersales" value="1" x-model="forAftersales"
                                    {{ old('for_aftersales', $marketingRequest->for_aftersales) ? 'checked' : '' }}
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-300">
                                <span class="text-sm text-gray-700">Aftersales</span>
                            </label>
                            <div class="flex gap-4" x-show="forAftersales" x-cloak x-transition>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="aftersales_vehicle_type" value="passenger_car"
                                        {{ old('aftersales_vehicle_type', $marketingRequest->aftersales_vehicle_type) === 'passenger_car' ? 'checked' : '' }}
                                        class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-300">
                                    <span class="text-sm text-gray-600">Passenger Car</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="aftersales_vehicle_type" value="commercial_vehicle"
                                        {{ old('aftersales_vehicle_type', $marketingRequest->aftersales_vehicle_type) === 'commercial_vehicle' ? 'checked' : '' }}
                                        class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-300">
                                    <span class="text-sm text-gray-600">Commercial Vehicle</span>
                                </label>
                            </div>
                        </div>

                        <!-- Others -->
                        <div class="flex flex-wrap items-center gap-4">
                            <label class="flex items-center gap-2 w-28 cursor-pointer">
                                <input type="checkbox" name="for_others" value="1" x-model="forOthers"
                                    {{ old('for_others', $marketingRequest->for_others) ? 'checked' : '' }}
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-300">
                                <span class="text-sm text-gray-700">Others</span>
                            </label>
                            <div x-show="forOthers" x-cloak x-transition class="flex-1 min-w-48">
                                <input type="text" name="others_description"
                                    value="{{ old('others_description', $marketingRequest->others_description) }}"
                                    placeholder="Please specify..."
                                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-400 outline-none transition">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card: Project Description -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm mb-5">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-sm font-semibold text-[#1D3557] uppercase tracking-wide">Project Description</h2>
                </div>
                <div class="p-6 space-y-5">
                    <!-- Purpose -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Purpose <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="purpose"
                            value="{{ old('purpose', $marketingRequest->purpose) }}"
                            placeholder="Brief purpose of this request..."
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-400 outline-none transition @error('purpose') border-red-400 @enderror">
                        @error('purpose')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Detail Concept -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Detail Concept</label>
                        <textarea name="detail_concept" rows="3" placeholder="Describe the concept in detail..."
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-400 outline-none transition resize-none @error('detail_concept') border-red-400 @enderror">{{ old('detail_concept', $marketingRequest->detail_concept) }}</textarea>
                        @error('detail_concept')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Further Information -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Further Information</label>
                        <textarea name="further_information" rows="3" placeholder="Any additional information..."
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-400 outline-none transition resize-none">{{ old('further_information', $marketingRequest->further_information) }}</textarea>
                    </div>

                    <!-- Reference Visual -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Reference Visual</label>
                        @if ($marketingRequest->reference_visual)
                            <div class="flex items-center gap-3 mb-2 p-3 bg-gray-50 border border-gray-200 rounded-lg">
                                <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                </svg>
                                <span class="text-sm text-gray-600 flex-1 truncate">Current:
                                    {{ basename($marketingRequest->reference_visual) }}</span>
                                <a href="{{ Storage::url($marketingRequest->reference_visual) }}" target="_blank"
                                    class="text-xs text-blue-600 hover:underline flex-shrink-0">View</a>
                            </div>
                        @endif
                        <div class="border-2 border-dashed border-gray-200 rounded-lg p-6 text-center hover:border-blue-300 transition-colors"
                            x-data="{ fileName: '' }" @dragover.prevent
                            @drop.prevent="fileName = $event.dataTransfer.files[0]?.name">
                            <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <label class="cursor-pointer">
                                <span class="text-sm text-blue-600 font-medium hover:underline"
                                    x-text="fileName || '{{ $marketingRequest->reference_visual ? 'Upload a replacement file' : 'Click to upload or drag & drop' }}'">
                                </span>
                                <input type="file" name="reference_visual" accept="image/*,.pdf" class="hidden"
                                    @change="fileName = $event.target.files[0]?.name">
                            </label>
                            <p class="text-xs text-gray-400 mt-1">JPG, PNG, PDF up to 10MB</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card: Output Media -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm mb-6">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-sm font-semibold text-[#1D3557] uppercase tracking-wide">Output Media <span
                            class="text-gray-400 font-normal text-xs">(if needed)</span></h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @php
                            $mediaOptions = [
                                'poster_a3' => 'Poster A3 (29.7x42)',
                                'poster_a4' => 'Poster A4 (21x29.7)',
                                'flyer_a5' => 'Flyer A5 (14.8x21)',
                                'booklet' => 'Booklet',
                                'voucher' => 'Voucher',
                                'x_banner' => 'X-Banner',
                                'backdrop' => 'Backdrop',
                                'banner' => 'Banner',
                                'sticker' => 'Sticker',
                            ];
                            $currentMedia = old('output_media', $marketingRequest->output_media ?? []);
                        @endphp
                        @foreach ($mediaOptions as $value => $label)
                            <label
                                class="flex items-center gap-2.5 p-3 border border-gray-100 rounded-lg hover:bg-blue-50 hover:border-blue-200 cursor-pointer transition-colors has-[:checked]:bg-blue-50 has-[:checked]:border-blue-300">
                                <input type="checkbox" name="output_media[]" value="{{ $value }}"
                                    {{ in_array($value, $currentMedia) ? 'checked' : '' }}
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-300">
                                <span class="text-sm text-gray-700">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Lokal Campaign Signature -->
            <div x-show="campaignType === 'lokal'" x-cloak x-transition
                class="bg-white rounded-xl border border-gray-100 shadow-sm mb-5">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-sm font-semibold text-[#1D3557] uppercase tracking-wide">Lokal Campaign Approval
                    </h2>
                </div>
                <div class="p-6">
                    <table class="w-full border border-gray-300 text-sm">
                        <thead>
                            <tr>
                                <th
                                    class="border border-gray-300 px-4 py-2 text-center text-gray-700 font-semibold w-1/3">
                                    Request By</th>
                                <th
                                    class="border border-gray-300 px-4 py-2 text-center text-gray-700 font-semibold w-1/3">
                                    Approve By<br><span class="text-xs font-normal text-gray-500">Sales / Aftersales
                                        Manager</span>
                                </th>
                                <th
                                    class="border border-gray-300 px-4 py-2 text-center text-gray-700 font-semibold w-1/3">
                                    Acknowledged By<br><span class="text-xs font-normal text-gray-500">Branch Manager /
                                        GM</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border border-gray-300 h-24 align-bottom px-4 py-3">
                                    <div class="space-y-1.5">
                                        <div class="flex items-end gap-1"><span
                                                class="text-xs text-gray-600 whitespace-nowrap">Name :</span>
                                            <div class="flex-1 border-b border-gray-400"></div>
                                        </div>
                                        <div class="flex items-end gap-1"><span
                                                class="text-xs text-gray-600 whitespace-nowrap">Date :</span>
                                            <div class="flex-1 border-b border-gray-400"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="border border-gray-300 h-24 align-bottom px-4 py-3">
                                    <div class="space-y-1.5">
                                        <div class="flex items-end gap-1"><span
                                                class="text-xs text-gray-600 whitespace-nowrap">Name :</span>
                                            <div class="flex-1 border-b border-gray-400"></div>
                                        </div>
                                        <div class="flex items-end gap-1"><span
                                                class="text-xs text-gray-600 whitespace-nowrap">Date :</span>
                                            <div class="flex-1 border-b border-gray-400"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="border border-gray-300 h-24 align-bottom px-4 py-3">
                                    <div class="space-y-1.5">
                                        <div class="flex items-end gap-1"><span
                                                class="text-xs text-gray-600 whitespace-nowrap">Name :</span>
                                            <div class="flex-1 border-b border-gray-400"></div>
                                        </div>
                                        <div class="flex items-end gap-1"><span
                                                class="text-xs text-gray-600 whitespace-nowrap">Date :</span>
                                            <div class="flex-1 border-b border-gray-400"></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Group Campaign Signature -->
            <div x-show="campaignType === 'group'" x-cloak x-transition
                class="bg-white rounded-xl border border-gray-100 shadow-sm mb-5">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-sm font-semibold text-[#1D3557] uppercase tracking-wide">Group Campaign Approval
                    </h2>
                </div>
                <div class="p-6">
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
                                <td class="border border-gray-300 h-24 align-bottom px-4 py-3">
                                    <div class="space-y-1.5">
                                        <div class="flex items-end gap-1"><span
                                                class="text-xs text-gray-600 whitespace-nowrap">Name :</span>
                                            <div class="flex-1 border-b border-gray-400"></div>
                                        </div>
                                        <div class="flex items-end gap-1"><span
                                                class="text-xs text-gray-600 whitespace-nowrap">Date :</span>
                                            <div class="flex-1 border-b border-gray-400"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="border border-gray-300 h-24 align-bottom px-4 py-3">
                                    <div class="space-y-1.5">
                                        <div class="flex items-end gap-1"><span
                                                class="text-xs text-gray-600 whitespace-nowrap">Name :</span>
                                            <div class="flex-1 border-b border-gray-400"></div>
                                        </div>
                                        <div class="flex items-end gap-1"><span
                                                class="text-xs text-gray-600 whitespace-nowrap">Date :</span>
                                            <div class="flex-1 border-b border-gray-400"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="border border-gray-300 h-24 align-bottom px-4 py-3">
                                    <div class="space-y-1.5">
                                        <div class="flex items-end gap-1"><span
                                                class="text-xs text-gray-600 whitespace-nowrap">Name :</span>
                                            <div class="flex-1 border-b border-gray-400"></div>
                                        </div>
                                        <div class="flex items-end gap-1"><span
                                                class="text-xs text-gray-600 whitespace-nowrap">Date :</span>
                                            <div class="flex-1 border-b border-gray-400"></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('requests.show', $marketingRequest) }}"
                    class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit"
                    class="px-6 py-2.5 text-sm font-medium text-white bg-[#1D3557] rounded-lg hover:bg-[#162840] transition-colors inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                    Resubmit Request
                </button>
            </div>
        </form>
    </div>

    <script>
        function requestForm() {
            return {
                forSales: {{ old('for_sales', $marketingRequest->for_sales) ? 'true' : 'false' }},
                forAftersales: {{ old('for_aftersales', $marketingRequest->for_aftersales) ? 'true' : 'false' }},
                forOthers: {{ old('for_others', $marketingRequest->for_others) ? 'true' : 'false' }},
                campaignType: '{{ old('campaign_type', $marketingRequest->is_local_campaign ? 'lokal' : ($marketingRequest->is_group_campaign ? 'group' : '')) }}',
            }
        }
    </script>
</x-app-layout>
