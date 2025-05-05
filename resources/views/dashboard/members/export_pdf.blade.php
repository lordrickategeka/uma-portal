<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Transactions Export</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .status-paid {
            color: green;
            font-weight: bold;
        }
        .status-pending {
            color: orange;
            font-weight: bold;
        }
        .status-failed {
            color: red;
            font-weight: bold;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $organizationName }}</h2>
        <h3>Transactions Report</h3>
        <p>Generated on: {{ $currentDate }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Reference</th>
                <th>UMA Number</th>
                <th>Member</th>
                <th>Plan</th>
                <th>Amount</th>
                <th>Payment Method</th>
                <th>Status</th>
                <th>Transaction Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
            <tr>
                <td>{{ $transaction->id }}</td>
                <td>{{ $transaction->reference }}</td>
                <td>{{ $transaction->uma_number ?? 'N/A' }}</td>
                <td>{{ $transaction->first_name }} {{ $transaction->last_name }}</td>
                <td>{{ $transaction->plan_name ?? 'N/A' }}</td>
                <td>{{ number_format($transaction->amount, 2) }} {{ $transaction->currency }}</td>
                <td>{{ $transaction->payment_method }}</td>
                <td class="status-{{ $transaction->status }}">{{ ucfirst($transaction->status) }}</td>
                <td>{{ $transaction->created_at->format('Y-m-d') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        <p>This report contains {{ count($transactions) }} transactions. Confidential information - do not distribute.</p>
        <p>{{ $organizationName }} &copy; {{ date('Y') }}</p>
    </div>
</body>
</html>