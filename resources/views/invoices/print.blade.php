<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاتورة - {{ $invoice->invoice_number }}</title>
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
        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .patient-info, .invoice-details {
            width: 48%;
        }
        .amounts-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .amounts-table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        .amounts-table .label {
            font-weight: bold;
            text-align: right;
        }
        .amounts-table .value {
            text-align: left;
        }
        .total-row {
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            font-weight: bold;
            font-size: 1.2em;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
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
        <h2>فاتورة</h2>
        <p>نظام إدارة العيادة</p>
    </div>

    <div class="invoice-info">
        <div class="patient-info">
            <p><strong>المريض:</strong> {{ $invoice->patient->full_name }}</p>
            <p><strong>رقم الهاتف:</strong> {{ $invoice->patient->phone_number }}</p>
        </div>
        <div class="invoice-details">
            <p><strong>رقم الفاتورة:</strong> {{ $invoice->invoice_number }}</p>
            <p><strong>التاريخ:</strong> {{ $invoice->created_at->format('Y-m-d') }}</p>
        </div>
    </div>

    <table class="amounts-table">
        <tr>
            <td class="label">رسوم الكشف:</td>
            <td class="value">{{ number_format($invoice->consultation_fee, 2) }} ج.م</td>
        </tr>
        <tr class="total-row">
            <td class="label">المبلغ الإجمالي:</td>
            <td class="value">{{ number_format($invoice->total_amount, 2) }} ج.م</td>
        </tr>
        @if($invoice->payments->count() > 0)
        <tr>
            <td class="label">المدفوع:</td>
            <td class="value">{{ number_format($invoice->paid_amount, 2) }} ج.م</td>
        </tr>
        <tr>
            <td class="label">المتبقي:</td>
            <td class="value">{{ number_format($invoice->remaining_amount, 2) }} ج.م</td>
        </tr>
        @endif
    </table>

    <div class="footer">
        <p><strong>الحالة:</strong> 
            @if($invoice->status == 'paid')
                مدفوعة
            @else
                غير مدفوعة
            @endif
        </p>
        <p>شكراً لزيارتكم</p>
    </div>

    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="window.print()" class="btn btn-primary">طباعة</button>
        <button onclick="window.close()" class="btn btn-secondary">إغلاق</button>
    </div>
</body>
</html>

