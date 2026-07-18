<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Patients Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .report-info {
            text-align: center;
            margin-bottom: 20px;
            color: #7f8c8d;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        thead {
            background-color: #3498db;
            color: white;
        }
        th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #bdc3c7;
        }
        td {
            padding: 10px 12px;
            border: 1px solid #bdc3c7;
            font-size: 12px;
        }
        tbody tr:nth-child(even) {
            background-color: #ecf0f1;
        }
        .status-active {
            color: #27ae60;
            font-weight: bold;
        }
        .status-inactive {
            color: #e74c3c;
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #bdc3c7;
            font-size: 11px;
            color: #7f8c8d;
        }
    </style>
</head>
<body>
    <h1>Patients Report</h1>
    <div class="report-info">
        Generated on {{ date('d M Y H:i:s') }}
        <br>
        Total Records: {{ count($patients) }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Patient Name</th>
                <th>Registration No</th>
                <th>Mobile</th>
                <th>Email</th>
                <th>Status</th>
                <th>Treatment</th>
                <th>City</th>
            </tr>
        </thead>
        <tbody>
            @forelse($patients as $patient)
                <tr>
                    <td>{{ $patient->FirstName }} {{ $patient->LastName ?? '' }}</td>
                    <td>{{ $patient->RegistrationNo ?? '—' }}</td>
                    <td>{{ $patient->Mobile ?? '—' }}</td>
                    <td>{{ $patient->EMail ?? '—' }}</td>
                    <td>
                        <span class="{{ $patient->CustomerStatus === 'Active' ? 'status-active' : 'status-inactive' }}">
                            {{ $patient->CustomerStatus ?? '—' }}
                        </span>
                    </td>
                    <td class="text-center">{{ $patient->TreatmentJoined === 'Yes' ? 'Joined' : 'Not Joined' }}</td>
                    <td>{{ $patient->City ?? '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No patients found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>&copy; {{ date('Y') }} Preclinic. All Rights Reserved.</p>
    </div>
</body>
</html>
