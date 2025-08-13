<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Payslip</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <h2>{{ getCompany()->name }} - Payslip</h2>
    <p>Employee: {{ $salary->employee->name }}</p>
    <p>Designation: {{ $salary->employee->designation->name }}</p>
    <p>Month: {{ \Carbon\Carbon::createFromDate($salary->payroll->year, $salary->payroll->month, 1)->format('F Y') }}</p>

    <table>
        <tr>
            <th>Gross Salary</th>
            <td>Rs {{ number_format($salary->gross_salary, 2) }}</td>
        </tr>
        <tr>
            <th>EPF</th>
            <td>Rs {{ number_format($salary->breakdown->getEpfDeduction(), 2) }}</td>
        </tr>
        <tr>
            <th>ETF</th>
            <td>Rs {{ number_format($salary->breakdown->getEtfDeduction(), 2) }}</td>
        </tr>
        <tr>
            <th>PAYE</th>
            <td>Rs {{ number_format($salary->breakdown->getMonthlyPIT(), 2) }}</td>
        </tr>
        <tr>
            <th>Net Pay</th>
            <td>Rs {{ number_format($salary->breakdown->getNetPayMonthly(), 2) }}</td>
        </tr>
    </table>
</body>
</html>
