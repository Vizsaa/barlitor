<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #{{ $transactionId }}</title>
</head>
<body style="font-family: DejaVu Sans, Arial, Helvetica, sans-serif; color:#111827; margin:0; padding:0;">
    <div style="padding:28px;">
        <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
            <tr>
                <td>
                    <div style="font-size:14px; letter-spacing:2px; text-transform:uppercase; color:#6b7280; font-weight:700;">
                        BruTor Shop
                    </div>
                    <div style="font-size:26px; font-weight:900; margin-top:6px;">
                        Transaction Receipt
                    </div>
                    <div style="margin-top:10px; font-size:12px; color:#6b7280;">
                        Receipt #: <span style="font-weight:800; color:#111827;">{{ $transactionId }}</span>
                        <span style="color:#d1d5db; padding:0 8px;">•</span>
                        Date: <span style="font-weight:800; color:#111827;">{{ $receiptDate }}</span>
                    </div>
                </td>
                <td align="right">
                    <div style="display:inline-block; padding:8px 12px; border-radius:999px; background:#fff7ed; border:1px solid #fdba74; color:#9a3412; font-weight:900; font-size:12px; text-transform:uppercase; letter-spacing:1px;">
                        Paid
                    </div>
                </td>
            </tr>
        </table>

        <div style="margin-top:16px; font-size:12px; color:#6b7280; line-height:1.6;">
            Customer: <span style="font-weight:800; color:#111827;">{{ $customerName }}</span>
        </div>

        @if(!empty($products))
            <div style="margin-top:18px; font-size:12px; font-weight:900; letter-spacing:2px; text-transform:uppercase; color:#374151;">
                Materials / Products
            </div>
            <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse; margin-top:8px; border:1px solid #e5e7eb;">
                <thead>
                    <tr style="background:#f9fafb;">
                        <th align="left" style="padding:10px; font-size:11px; color:#6b7280; text-transform:uppercase; letter-spacing:1px; border-bottom:1px solid #e5e7eb;">Item</th>
                        <th align="center" style="padding:10px; font-size:11px; color:#6b7280; text-transform:uppercase; letter-spacing:1px; border-bottom:1px solid #e5e7eb;">Qty</th>
                        <th align="right" style="padding:10px; font-size:11px; color:#6b7280; text-transform:uppercase; letter-spacing:1px; border-bottom:1px solid #e5e7eb;">Rate</th>
                        <th align="right" style="padding:10px; font-size:11px; color:#6b7280; text-transform:uppercase; letter-spacing:1px; border-bottom:1px solid #e5e7eb;">Line Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $p)
                        <tr>
                            <td style="padding:10px; border-bottom:1px solid #f3f4f6; font-size:12px;">{{ $p['title'] }}</td>
                            <td align="center" style="padding:10px; border-bottom:1px solid #f3f4f6; font-size:12px;">{{ (int)$p['quantity'] }}</td>
                            <td align="right" style="padding:10px; border-bottom:1px solid #f3f4f6; font-size:12px;">₱{{ number_format($p['price'], 2) }}</td>
                            <td align="right" style="padding:10px; border-bottom:1px solid #f3f4f6; font-size:12px; font-weight:900;">₱{{ number_format($p['line_total'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        @if(!empty($tools))
            <div style="margin-top:18px; font-size:12px; font-weight:900; letter-spacing:2px; text-transform:uppercase; color:#374151;">
                Tool Rentals
            </div>
            <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse; margin-top:8px; border:1px solid #e5e7eb;">
                <thead>
                    <tr style="background:#f9fafb;">
                        <th align="left" style="padding:10px; font-size:11px; color:#6b7280; text-transform:uppercase; letter-spacing:1px; border-bottom:1px solid #e5e7eb;">Tool</th>
                        <th align="center" style="padding:10px; font-size:11px; color:#6b7280; text-transform:uppercase; letter-spacing:1px; border-bottom:1px solid #e5e7eb;">Period</th>
                        <th align="center" style="padding:10px; font-size:11px; color:#6b7280; text-transform:uppercase; letter-spacing:1px; border-bottom:1px solid #e5e7eb;">Qty</th>
                        <th align="right" style="padding:10px; font-size:11px; color:#6b7280; text-transform:uppercase; letter-spacing:1px; border-bottom:1px solid #e5e7eb;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tools as $t)
                        <tr>
                            <td style="padding:10px; border-bottom:1px solid #f3f4f6; font-size:12px;">{{ $t['title'] }}</td>
                            <td align="center" style="padding:10px; border-bottom:1px solid #f3f4f6; font-size:11px; color:#374151;">
                                {{ $t['start_date'] }} → {{ $t['due_date'] }}
                            </td>
                            <td align="center" style="padding:10px; border-bottom:1px solid #f3f4f6; font-size:12px;">{{ (int)$t['quantity'] }}</td>
                            <td align="right" style="padding:10px; border-bottom:1px solid #f3f4f6; font-size:12px; font-weight:900;">₱{{ number_format($t['line_total'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse; margin-top:18px;">
            <tr>
                <td></td>
                <td style="width:320px;">
                    <div style="border:1px solid #e5e7eb; border-radius:10px; padding:12px 14px; background:#ffffff;">
                        <div style="font-size:12px; font-weight:900; letter-spacing:2px; text-transform:uppercase; color:#6b7280; margin-bottom:8px;">
                            Payment Summary
                        </div>
                        <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse; font-size:12px;">
                            <tr>
                                <td style="padding:6px 0; color:#6b7280;">Grand Total</td>
                                <td align="right" style="padding:6px 0; font-weight:900;">₱{{ number_format($grandTotal, 2) }}</td>
                            </tr>
                            <tr>
                                <td style="padding:6px 0; color:#6b7280;">Amount Paid</td>
                                <td align="right" style="padding:6px 0; font-weight:800;">₱{{ number_format($amountPaid, 2) }}</td>
                            </tr>
                            <tr>
                                <td style="padding:6px 0; color:#6b7280;">Change</td>
                                <td align="right" style="padding:6px 0; font-weight:900; color:#065f46;">₱{{ number_format($change, 2) }}</td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>

        <div style="margin-top:18px; font-size:10px; color:#9ca3af; line-height:1.6;">
            © {{ date('Y') }} BruTor Shop. This receipt is generated automatically.
        </div>
    </div>
</body>
</html>

