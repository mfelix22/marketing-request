<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Request #{{ str_pad($request->id, 4, '0', STR_PAD_LEFT) }} — Hartono Group</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            color: #1a1a1a;
            background: #fff;
            padding: 24px;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #1D3557;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .header-left img {
            height: 48px;
        }

        .header-left .brand {}

        .header-left .brand .name {
            font-size: 20px;
            font-weight: 800;
            color: #1D3557;
            letter-spacing: 2px;
        }

        .header-left .brand .sub {
            font-size: 11px;
            color: #6b7280;
            letter-spacing: 3px;
        }

        .header-right {
            text-align: right;
        }

        .header-right .title {
            font-size: 16px;
            font-weight: 700;
            color: #1D3557;
        }

        .header-right .ref {
            font-size: 11px;
            color: #6b7280;
            margin-top: 2px;
        }

        .status-badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .status-submitted {
            background: #fef9c3;
            color: #854d0e;
        }

        .status-under_review {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-approved {
            background: #dcfce7;
            color: #166534;
        }

        .status-rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .section {
            margin-bottom: 16px;
        }

        .section-title {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #1D3557;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 4px;
            margin-bottom: 10px;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px 24px;
        }

        .grid-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 10px 16px;
        }

        .label {
            font-size: 10px;
            color: #6b7280;
            margin-bottom: 2px;
        }

        .value {
            font-size: 12px;
            color: #111827;
            font-weight: 500;
        }

        .badge-list {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-top: 4px;
        }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 500;
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #e5e7eb;
        }

        .text-block {
            font-size: 12px;
            color: #374151;
            white-space: pre-line;
            line-height: 1.6;
        }

        .sig-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }

        .sig-table th {
            border: 1px solid #9ca3af;
            padding: 6px 10px;
            text-align: center;
            font-size: 11px;
            color: #374151;
            background: #f9fafb;
        }

        .sig-table td {
            border: 1px solid #9ca3af;
            padding: 6px 10px;
            height: 80px;
            vertical-align: bottom;
        }

        .sig-line-row {
            display: flex;
            align-items: flex-end;
            gap: 4px;
            margin-top: 6px;
        }

        .sig-line-label {
            font-size: 10px;
            color: #6b7280;
            white-space: nowrap;
        }

        .sig-line {
            flex: 1;
            border-bottom: 1px solid #6b7280;
            padding-bottom: 1px;
            font-size: 10px;
            font-weight: 600;
            color: #111827;
        }

        .approval-row {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            padding: 6px 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .approval-icon {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 10px;
            font-weight: 700;
        }

        .icon-approved {
            background: #dcfce7;
            color: #166534;
        }

        .icon-rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .footer {
            margin-top: 24px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
            font-size: 10px;
            color: #9ca3af;
            display: flex;
            justify-content: space-between;
        }

        @media print {
            body {
                padding: 12px;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body>
    <!-- Print button (hidden on print) -->
    <div class="no-print" style="text-align:right; margin-bottom:16px;">
        <button onclick="window.print()"
            style="padding:8px 18px; background:#1D3557; color:white; border:none; border-radius:6px; font-size:13px; cursor:pointer;">
            🖨 Print / Save as PDF
        </button>
        <button onclick="window.close()"
            style="padding:8px 14px; background:#f3f4f6; color:#374151; border:1px solid #d1d5db; border-radius:6px; font-size:13px; cursor:pointer; margin-left:8px;">
            Close
        </button>
    </div>

    <!-- Header -->
    <div class="header">
        <div class="header-left">
            <img src="/hartonogroup.png" alt="Hartono Group">
            <div class="brand">
                <div class="name">HARTONO</div>
                <div class="sub">GROUP</div>
            </div>
        </div>
        <div class="header-right">
            <div class="title">Marketing Request Form</div>
            <div class="ref">Ref: MR-{{ str_pad($request->id, 4, '0', STR_PAD_LEFT) }} &nbsp;|&nbsp;
                <span class="status-badge status-{{ $request->status }}">{{ $request->status_label }}</span>
            </div>
        </div>
    </div>

    <!-- Basic Info -->
    <div class="section">
        <div class="section-title">Request Information</div>
        <div class="grid">
            <div>
                <div class="label">PIC Name</div>
                <div class="value">{{ $request->pic_name }}</div>
            </div>
            <div>
                <div class="label">Department</div>
                <div class="value">{{ $request->department?->name ?? '—' }}</div>
            </div>
            <div>
                <div class="label">Date Request</div>
                <div class="value">{{ $request->date_request->format('d F Y') }}</div>
            </div>
            <div>
                <div class="label">Deadline</div>
                <div class="value">{{ $request->deadline->format('d F Y') }}</div>
            </div>
            <div>
                <div class="label">Submitted By</div>
                <div class="value">{{ $request->user->name }}</div>
            </div>
            <div>
                <div class="label">Submitted At</div>
                <div class="value">{{ $request->created_at->format('d F Y, H:i') }}</div>
            </div>
        </div>
    </div>

    <!-- Project For -->
    <div class="section">
        <div class="section-title">Project For</div>
        <div class="badge-list">
            @if ($request->is_local_campaign)
                <span class="badge">Lokal Campaign</span>
            @endif
            @if ($request->is_group_campaign)
                <span class="badge">Group Campaign</span>
            @endif
            @if ($request->for_sales)
                <span class="badge">Sales —
                    {{ ucwords(str_replace('_', ' ', $request->sales_vehicle_type ?? '')) }}</span>
            @endif
            @if ($request->for_aftersales)
                <span class="badge">Aftersales —
                    {{ ucwords(str_replace('_', ' ', $request->aftersales_vehicle_type ?? '')) }}</span>
            @endif
            @if ($request->for_others)
                <span class="badge">Others: {{ $request->others_description }}</span>
            @endif
        </div>
    </div>

    <!-- Project Description -->
    <div class="section">
        <div class="section-title">Project Description</div>
        <div class="label">Purpose</div>
        <div class="text-block" style="margin-bottom:8px;">{{ $request->purpose }}</div>
        @if ($request->detail_concept)
            <div class="label" style="margin-top:6px;">Detail Concept</div>
            <div class="text-block" style="margin-bottom:8px;">{{ $request->detail_concept }}</div>
        @endif
        @if ($request->further_information)
            <div class="label" style="margin-top:6px;">Further Information</div>
            <div class="text-block">{{ $request->further_information }}</div>
        @endif
    </div>

    <!-- Output Media -->
    @if ($request->output_media && count($request->output_media) > 0)
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
        <div class="section">
            <div class="section-title">Output Media Required</div>
            <div class="badge-list">
                @foreach ($request->output_media as $m)
                    <span class="badge">{{ $mediaLabels[$m] ?? $m }}</span>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Approval History -->
    @if ($request->approvals->isNotEmpty())
        <div class="section">
            <div class="section-title">Approval History</div>
            @foreach ($request->approvals->sortBy('acted_at') as $approval)
                <div class="approval-row">
                    <div
                        class="approval-icon {{ $approval->status === 'approved' ? 'icon-approved' : 'icon-rejected' }}">
                        {{ $approval->status === 'approved' ? '✓' : '✗' }}
                    </div>
                    <div>
                        <strong>{{ $approval->approver->name }}</strong>
                        <span
                            style="font-size:10px; color:#6b7280; margin-left:6px; text-transform:capitalize;">{{ $approval->status }}</span>
                        <span
                            style="font-size:10px; color:#9ca3af; margin-left:6px;">{{ $approval->acted_at->format('d M Y, H:i') }}</span>
                        @if ($approval->comment)
                            <div style="font-size:11px; color:#374151; margin-top:2px; font-style:italic;">
                                "{{ $approval->comment }}"</div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Signature Section -->
    @if ($request->is_local_campaign || $request->is_group_campaign)
        @php $approvalsList = $request->approvals->sortBy('acted_at')->values(); @endphp
        <div class="section">
            <div class="section-title">
                {{ $request->is_local_campaign ? 'Lokal Campaign Approval' : 'Group Campaign Approval' }}</div>
            @if ($request->is_local_campaign)
                <table class="sig-table">
                    <thead>
                        <tr>
                            <th>Request By</th>
                            <th>Approve By<br><span style="font-weight:400; font-size:10px;">Sales / Aftersales
                                    Manager</span></th>
                            <th>Acknowledged By<br><span style="font-weight:400; font-size:10px;">Branch Manager /
                                    GM</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="sig-line-row"><span class="sig-line-label">Name :</span><span
                                        class="sig-line">{{ $request->user->name }}</span></div>
                                <div class="sig-line-row"><span class="sig-line-label">Date :</span><span
                                        class="sig-line">{{ $request->created_at->format('d M Y') }}</span></div>
                            </td>
                            @php $a1 = $approvalsList->get(0); @endphp
                            <td>
                                <div class="sig-line-row"><span class="sig-line-label">Name :</span><span
                                        class="sig-line">{{ $a1?->approver->name ?? '' }}</span></div>
                                <div class="sig-line-row"><span class="sig-line-label">Date :</span><span
                                        class="sig-line">{{ $a1?->acted_at?->format('d M Y') ?? '' }}</span></div>
                            </td>
                            @php $a2 = $approvalsList->get(1); @endphp
                            <td>
                                <div class="sig-line-row"><span class="sig-line-label">Name :</span><span
                                        class="sig-line">{{ $a2?->approver->name ?? '' }}</span></div>
                                <div class="sig-line-row"><span class="sig-line-label">Date :</span><span
                                        class="sig-line">{{ $a2?->acted_at?->format('d M Y') ?? '' }}</span></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            @else
                <table class="sig-table">
                    <thead>
                        <tr>
                            <th>Request By<br><span style="font-weight:400; font-size:10px;">Marketing
                                    Corporate</span></th>
                            <th>Approve By<br><span style="font-weight:400; font-size:10px;">Aftersales
                                    Manager</span></th>
                            <th>Acknowledged By<br><span style="font-weight:400; font-size:10px;">Albert
                                    (Director)</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            {{-- Request By: auto-filled with creator --}}
                            <td>
                                <div class="sig-line-row"><span class="sig-line-label">Name :</span><span
                                        class="sig-line">{{ $request->user->name }}</span></div>
                                <div class="sig-line-row"><span class="sig-line-label">Date :</span><span
                                        class="sig-line">{{ $request->created_at->format('d M Y') }}</span></div>
                            </td>
                            {{-- Approve By & Acknowledged By: from approval records --}}
                            @foreach ([0, 1] as $i)
                                @php $ap = $approvalsList->get($i); @endphp
                                <td>
                                    <div class="sig-line-row"><span class="sig-line-label">Name :</span><span
                                            class="sig-line">{{ $ap?->approver->name ?? '' }}</span></div>
                                    <div class="sig-line-row"><span class="sig-line-label">Date :</span><span
                                            class="sig-line">{{ $ap?->acted_at?->format('d M Y') ?? '' }}</span></div>
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            @endif
        </div>
    @endif

    <div class="footer">
        <span>Hartono Group — Marketing Request System</span>
        <span>Printed on {{ now()->format('d F Y, H:i') }} by {{ auth()->user()->name }}</span>
    </div>
</body>

</html>
