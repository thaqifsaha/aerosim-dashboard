<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1e293b;
            background: #ffffff;
            line-height: 1.5;
        }

        /* ── Header banner ── */
        .report-header {
            background-color: #0a1628;
            color: #ffffff;
            padding: 14px 18px;
            margin-bottom: 0;
        }
        .header-inner {
            width: 100%;
            border-collapse: collapse;
        }
        .header-inner td {
            border: none;
            vertical-align: middle;
            padding: 0;
        }
        .header-title-cell { width: 70%; }
        .header-logo-cell  { width: 30%; text-align: right; }

        .report-title {
            font-size: 20px;
            font-weight: bold;
            color: #ffffff;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .report-subtitle {
            font-size: 10px;
            color: #7ec8e3;
            margin-top: 3px;
            letter-spacing: 0.5px;
        }
        .report-generated {
            font-size: 9.5px;
            color: #94a3b8;
            margin-top: 2px;
        }

        /* ── Cyan accent divider ── */
        .accent-bar {
            height: 3px;
            background-color: #00bfff;
            margin-bottom: 16px;
        }

        /* ── Section headers ── */
        h2 {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #0a1628;
            padding: 6px 10px;
            background-color: #e8f4f8;
            border-left: 3px solid #00bfff;
            margin-top: 16px;
            margin-bottom: 6px;
        }

        /* ── Data tables ── */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }
        table.data-table th {
            width: 35%;
            background-color: #f1f5f9;
            border: 1px solid #cbd5e1;
            padding: 5px 8px;
            font-weight: bold;
            color: #475569;
            text-align: left;
            font-size: 10.5px;
        }
        table.data-table td {
            border: 1px solid #cbd5e1;
            padding: 5px 8px;
            color: #1e293b;
            font-size: 11px;
        }
        table.data-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }

        /* ── DSS score cell ── */
        .score-value {
            font-weight: bold;
            font-size: 12px;
            color: #0a1628;
        }
        .total-score-row th,
        .total-score-row td {
            background-color: #e8f4f8;
            font-weight: bold;
        }

        /* ── Pass/Fail badges ── */
        .badge-pass {
            color: #166534;
            font-weight: bold;
            font-size: 11px;
            padding: 2px 8px;
        }
        .badge-fail {
            color: #991b1b;
            font-weight: bold;
            font-size: 11px;
            padding: 2px 8px;
        }

        /* ── Events list ── */
        .events-table {
            width: 100%;
            border-collapse: collapse;
        }
        .events-table td {
            border: 1px solid #cbd5e1;
            padding: 5px 8px;
            font-size: 11px;
            color: #1e293b;
        }
        .event-bullet {
            width: 16px;
            text-align: center;
            color: #ef4444;
            font-weight: bold;
        }
        .event-ok {
            color: #16a34a;
        }

        /* ── Signature block ── */
        .signature-section {
            margin-top: 50px;
            width: 100%;
        }
        .sig-block {
            width: 38%;
            float: right;
            text-align: center;
        }
        .sig-line {
            border-top: 1px solid #334155;
            width: 85%;
            margin: 6px auto 0 auto;
        }
        .sig-label {
            font-size: 10.5px;
            font-weight: bold;
            color: #475569;
            margin-top: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .clearfix { clear: both; }

        /* ── Footer ── */
        .report-footer {
            margin-top: 24px;
            border-top: 1px solid #e2e8f0;
            padding-top: 6px;
            font-size: 9px;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>
<body>

    {{-- ── Header banner ── --}}
    <div class="report-header">
        <table class="header-inner">
            <tr>
                <td class="header-title-cell">
                    <div class="report-title">Flight Evaluation Report</div>
                    <div class="report-subtitle">AeroSim Flight Simulator Training System</div>
                    <div class="report-generated">Generated: {{ now()->format('d M Y, H:i') }}</div>
                </td>
                <td class="header-logo-cell">
                    <img src="file://{{ public_path('images/aerosim_logo.jpg') }}" height="55" alt="AeroSim">
                </td>
            </tr>
        </table>
    </div>
    <div class="accent-bar"></div>

    {{-- ── Session Information ── --}}
    <h2>Session Information</h2>
    <table class="data-table">
        <tr><th>Pilot</th><td>{{ $session->user->name ?? 'Unknown' }}</td></tr>
        <tr><th>Session ID</th><td>#{{ $session->id }}</td></tr>
        <tr><th>Flight Date</th><td>{{ \Carbon\Carbon::parse($session->flight_date)->format('d M Y') }}</td></tr>
        <tr><th>Aircraft Type</th><td>{{ $session->aircraft_type }}</td></tr>
        <tr><th>Duration</th><td>{{ $session->formatted_duration }}</td></tr>
        <tr><th>Start Time</th><td>{{ \Carbon\Carbon::parse($session->start_time)->format('H:i:s') }}</td></tr>
        <tr><th>End Time</th><td>{{ \Carbon\Carbon::parse($session->end_time)->format('H:i:s') }}</td></tr>
    </table>

    {{-- ── DSS Evaluation Result ── --}}
    <h2>DSS Evaluation Result</h2>
    <table class="data-table">
        <tr>
            <th>Control Smoothness</th>
            <td><span class="score-value">{{ $session->dssResult->control_smoothness_score ?? 'N/A' }}</span></td>
        </tr>
        <tr>
            <th>Altitude Accuracy</th>
            <td><span class="score-value">{{ $session->dssResult->altitude_accuracy_score ?? 'N/A' }}</span></td>
        </tr>
        <tr>
            <th>Airspeed Accuracy</th>
            <td><span class="score-value">{{ $session->dssResult->airspeed_accuracy_score ?? 'N/A' }}</span></td>
        </tr>
        <tr>
            <th>Safety Score</th>
            <td><span class="score-value">{{ $session->dssResult->safety_score ?? 'N/A' }}</span></td>
        </tr>
        <tr class="total-score-row">
            <th>Total Score</th>
            <td><span class="score-value">{{ $session->dssResult->total_score ?? 'N/A' }}</span></td>
        </tr>
        <tr>
            <th>Result</th>
            <td>
                <span class="{{ optional($session->dssResult)->pass_fail === 'PASS' ? 'badge-pass' : 'badge-fail' }}">
                    {{ $session->dssResult->pass_fail ?? 'N/A' }}
                </span>
            </td>
        </tr>
        <tr>
            <th>Decision Reason</th>
            <td>{{ $session->dssResult->decision_reason ?? 'N/A' }}</td>
        </tr>
    </table>

    {{-- ── Detected Events ── --}}
    <h2>Detected Events</h2>
    <table class="events-table">
        @if(optional($session->dssResult)->excessive_g_event)
            <tr><td class="event-bullet">!</td><td>Excessive G-Force</td></tr>
        @endif
        @if(optional($session->dssResult)->stall_event)
            <tr><td class="event-bullet">!</td><td>Stall Detected</td></tr>
        @endif
        @if(optional($session->dssResult)->hard_landing_event)
            <tr><td class="event-bullet">!</td><td>Hard Landing</td></tr>
        @endif
        @if(optional($session->dssResult)->crash_event)
            <tr><td class="event-bullet">!</td><td>Crash Detected — {{ $session->dssResult->crash_severity ?? 'Unknown' }}</td></tr>
        @endif
        @if(optional($session->dssResult)->unstable_flight_event)
            <tr><td class="event-bullet">!</td><td>Unstable Flight</td></tr>
        @endif
        @if(optional($session->dssResult)->overbank_event)
            <tr><td class="event-bullet">!</td><td>Overbank Warning</td></tr>
        @endif
        @if(
            !optional($session->dssResult)->excessive_g_event &&
            !optional($session->dssResult)->stall_event &&
            !optional($session->dssResult)->hard_landing_event &&
            !optional($session->dssResult)->crash_event &&
            !optional($session->dssResult)->unstable_flight_event &&
            !optional($session->dssResult)->overbank_event
        )
            <tr><td class="event-bullet event-ok">✓</td><td>No issues detected</td></tr>
        @endif
    </table>

    {{-- ── Signature block ── --}}
    <div class="signature-section">
        <div class="sig-block">
            <img src="file://{{ public_path('images/sign_thaqif.jpg') }}" height="80" alt="Signature">
            <div class="sig-line"></div>
            <div class="sig-label">Instructor Signature</div>
        </div>
        <div class="clearfix"></div>
    </div>

    {{-- ── Footer ── --}}
    <div class="report-footer">
        AeroSim Flight Simulator Training System &nbsp;&mdash;&nbsp; This report is computer-generated and for training reference only.
    </div>

</body>
</html>
