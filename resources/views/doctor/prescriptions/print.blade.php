<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>وصفة طبية - {{ $prescription->patient->full_name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .patient-info {
            margin-bottom: 20px;
        }
        .medicines-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .medicines-table th,
        .medicines-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: right;
        }
        .medicines-table th {
            background-color: #f0f0f0;
        }
        .footer {
            margin-top: 40px;
            text-align: left;
            border-top: 2px solid #000;
            padding-top: 20px;
        }
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>وصفة طبية</h2>
        <p>نظام إدارة العيادة</p>
    </div>

    <div class="patient-info">
        <p><strong>المريض:</strong> {{ $prescription->patient->full_name }}</p>
        <p><strong>الطبيب:</strong> {{ $prescription->doctor->name }}</p>
        <p><strong>التاريخ:</strong> {{ $prescription->created_at->format('Y-m-d') }}</p>
    </div>

    @if($prescription->items->count() > 0)
    <table class="medicines-table">
        <thead>
            <tr>
                <th>اسم الدواء</th>
                <th>الجرعة</th>
                <th>التكرار</th>
                <th>المدة</th>
                <th>تعليمات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($prescription->items as $item)
            <tr>
                <td><strong>{{ $item->medicine_name }}</strong></td>
                <td>{{ $item->dosage }}</td>
                <td>{{ $item->frequency }}</td>
                <td>{{ $item->duration }}</td>
                <td>{{ $item->instructions ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if($prescription->notes)
    <div style="margin-top: 20px;">
        <p><strong>ملاحظات:</strong></p>
        <p>{{ $prescription->notes }}</p>
    </div>
    @endif

    <div class="footer">
        <p><strong>توقيع الطبيب:</strong> _________________</p>
        <p>{{ $prescription->doctor->name }}</p>
    </div>

    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="window.print()" class="btn btn-primary">طباعة</button>
        <button onclick="window.close()" class="btn btn-secondary">إغلاق</button>
    </div>
</body>
</html>

