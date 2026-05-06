<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <table class="header-table">
        <tr>
            <td class="header-left">
                <h1>Flight Evaluation Report</h1>
                <p>Generated on: {{ now()->format('Y-m-d H:i') }}</p>
            </td>

            <td class="header-right">
                <img src="file://{{ public_path('images/aerosim_logo.jpg') }}" width="150">
            </td>
        </tr>
    </table>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111; }
        h1 { font-size: 22px; margin-bottom: 5px; }
        h2 {
            font-size: 15px;
            margin-top: 14px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 3px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }
        td, th {
            border: 1px solid #ccc;
            padding: 5px;
            text-align: left;
        }
        .badge-pass { color: green; font-weight: bold; }
        .badge-fail { color: red; font-weight: bold; }
        .header-table {
            width: 100%;
            margin-bottom: 10px;
            border-collapse: collapse;
            border: none;
        }

        .header-table td {
            border: none;
            vertical-align: middle;
            padding: 0;
        }

        .header-left {
            width: 75%;
            text-align: left;
        }

        .header-right {
            width: 25%;
            text-align: right;
        }

        .header-left h1 {
            margin: 0;
            font-size: 24px;
        }

        .header-left p {
            margin: 3px 0 0 0;
            font-size: 12px;
        }
    </style>
</head>
<body>

<h2>Session Information</h2>
<table>
    <tr><th>Pilot</th><td>{{ $session->user->name ?? 'Unknown' }}</td></tr>
    <tr><th>Session ID</th><td>{{ $session->id }}</td></tr>
    <tr><th>Flight Date</th><td>{{ \Carbon\Carbon::parse($session->flight_date)->format('d/m/y') }}</td></tr>
    <tr><th>Aircraft Type</th><td>{{ $session->aircraft_type }}</td></tr>
    <tr><th>Duration</th><td>{{ $session->duration_sec }} sec</td></tr>
    <tr><th>Start Time</th><td>{{ \Carbon\Carbon::parse($session->start_time)->format('H:i:s') }}</td></tr>
    <tr><th>End Time</th><td>{{ \Carbon\Carbon::parse($session->end_time)->format('H:i:s') }}</td></tr>
</table>

<h2>DSS Evaluation Result</h2>
<table>
    <tr><th>Control Smoothness</th><td>{{ $session->dssResult->control_smoothness_score ?? 'N/A' }}</td></tr>
    <tr><th>Altitude Accuracy</th><td>{{ $session->dssResult->altitude_accuracy_score ?? 'N/A' }}</td></tr>
    <tr><th>Airspeed Accuracy</th><td>{{ $session->dssResult->airspeed_accuracy_score ?? 'N/A' }}</td></tr>
    <tr><th>Safety Score</th><td>{{ $session->dssResult->safety_score ?? 'N/A' }}</td></tr>
    <tr><th>Total Score</th><td>{{ $session->dssResult->total_score ?? 'N/A' }}</td></tr>
    <tr>
        <th>Result</th>
        <td class="{{ optional($session->dssResult)->pass_fail === 'PASS' ? 'badge-pass' : 'badge-fail' }}">
            {{ $session->dssResult->pass_fail ?? 'N/A' }}
        </td>
    </tr>
    <tr><th>Decision Reason</th><td>{{ $session->dssResult->decision_reason ?? 'N/A' }}</td></tr>
</table>

<h2>Detected Events</h2>
<ul>
    @if(optional($session->dssResult)->excessive_g_event)
        <li>Excessive G-Force</li>
    @endif

    @if(optional($session->dssResult)->stall_event)
        <li>Stall Detected</li>
    @endif

    @if(optional($session->dssResult)->hard_landing_event)
        <li>Hard Landing</li>
    @endif

    @if(optional($session->dssResult)->crash_event)
        <li>Crash Detected - {{ $session->dssResult->crash_severity ?? 'Unknown' }}</li>
    @endif

    @if(optional($session->dssResult)->unstable_flight_event)
        <li>Unstable Flight</li>
    @endif

    @if(optional($session->dssResult)->overbank_event)
        <li>Overbank Warning</li>
    @endif

    @if(
        !optional($session->dssResult)->excessive_g_event &&
        !optional($session->dssResult)->stall_event &&
        !optional($session->dssResult)->hard_landing_event &&
        !optional($session->dssResult)->crash_event &&
        !optional($session->dssResult)->unstable_flight_event &&
        !optional($session->dssResult)->overbank_event
    )
        <li>No issues detected</li>
    @endif
</ul>
<br>
<div style="width: 100%; margin-top: 60px;">

    <div style="width: 40%; float: right; text-align: center;">
        
        <img src="file://{{ public_path('images/sign_thaqif.jpg') }}" height="80">

        <div style="margin-top: 5px; border-top: 1px solid #000; width: 80%; margin-left: auto; margin-right: auto;"></div>

        <p style="margin-top: 5px; font-size: 12px; font-weight: bold;">
            Instructor Signature
        </p>

    </div>

    <div style="clear: both;"></div>
</div>

</body>
</html>