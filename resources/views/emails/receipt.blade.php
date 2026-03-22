<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #{{ $transactionId }}</title>
</head>
<body style="margin:0;padding:0;background:#0b0b0b;font-family:Arial, Helvetica, sans-serif;color:#e5e7eb;">
    <div style="width:100%;padding:24px 12px;background:#0b0b0b;">
        <div style="max-width:720px;margin:0 auto;background:#111111;border:1px solid #1f2937;border-radius:16px;overflow:hidden;">
            <!-- Header -->
            <div style="padding:20px 22px;background:linear-gradient(90deg,#111111 0%, #1a1a1a 55%, #111111 100%);border-bottom:1px solid #1f2937;">
                <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
                    <div>
                        <div style="font-size:14px;letter-spacing:2px;text-transform:uppercase;color:#9ca3af;font-weight:700;">
                            BarliTor Shop
                        </div>
                        <div style="font-size:22px;font-weight:900;color:#ffffff;margin-top:4px;">
                            Transaction Receipt
                        </div>
                        <div style="font-size:12px;color:#9ca3af;margin-top:6px;">
                            Receipt #: <span style="color:#fde68a;font-weight:700;">{{ $transactionId }}</span>
                            <span style="color:#374151;padding:0 8px;">•</span>
                            Date: <span style="color:#e5e7eb;font-weight:700;">{{ $receiptDate }}</span>
                        </div>
                    </div>
                    <div style="padding:10px 12px;border-radius:999px;background:rgba(249,115,22,0.14);border:1px solid rgba(249,115,22,0.35);color:#fdba74;font-weight:800;font-size:12px;letter-spacing:1px;text-transform:uppercase;">
                        Paid
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div style="padding:22px;">
                <div style="font-size:13px;color:#9ca3af;line-height:1.6;">
                    Hi {{ $customerName }}, thanks for shopping with BarliTor. Here’s a summary of your transaction.
                </div>

                <!-- Quick Actions -->
                <div style="margin-top:14px;display:flex;gap:10px;flex-wrap:wrap;">
                    @if(!empty($downloadUrl))
                        <a href="{{ $downloadUrl }}"
                           style="display:inline-block;text-decoration:none;background:#f97316;color:#ffffff;font-weight:900;font-size:12px;letter-spacing:1px;text-transform:uppercase;padding:10px 14px;border-radius:12px;border:1px solid #ea580c;">
                            Download PDF Receipt
                        </a>
                    @endif
                    <span style="display:inline-block;font-size:12px;color:#9ca3af;align-self:center;">
                        PDF is also attached to this email.
                    </span>
                </div>

                <!-- Products -->
                @if(!empty($products))
                    <div style="margin-top:18px;">
                        <div style="font-size:12px;font-weight:800;letter-spacing:2px;text-transform:uppercase;color:#d1d5db;margin-bottom:10px;">
                            Materials / Products
                        </div>
                        <div style="border:1px solid #1f2937;border-radius:12px;overflow:hidden;">
                            <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;">
                                <thead>
                                    <tr style="background:#1a1a1a;">
                                        <th align="left" style="padding:10px 12px;font-size:11px;color:#9ca3af;text-transform:uppercase;letter-spacing:1px;border-bottom:1px solid #1f2937;">Item</th>
                                        <th align="center" style="padding:10px 12px;font-size:11px;color:#9ca3af;text-transform:uppercase;letter-spacing:1px;border-bottom:1px solid #1f2937;">Qty</th>
                                        <th align="right" style="padding:10px 12px;font-size:11px;color:#9ca3af;text-transform:uppercase;letter-spacing:1px;border-bottom:1px solid #1f2937;">Rate</th>
                                        <th align="right" style="padding:10px 12px;font-size:11px;color:#9ca3af;text-transform:uppercase;letter-spacing:1px;border-bottom:1px solid #1f2937;">Line Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $p)
                                        <tr>
                                            <td style="padding:10px 12px;border-bottom:1px solid #1f2937;color:#f3f4f6;font-size:13px;">{{ $p['title'] }}</td>
                                            <td align="center" style="padding:10px 12px;border-bottom:1px solid #1f2937;color:#d1d5db;font-size:13px;">{{ (int)$p['quantity'] }}</td>
                                            <td align="right" style="padding:10px 12px;border-bottom:1px solid #1f2937;color:#d1d5db;font-size:13px;">₱{{ number_format($p['price'], 2) }}</td>
                                            <td align="right" style="padding:10px 12px;border-bottom:1px solid #1f2937;color:#ffffff;font-weight:800;font-size:13px;">₱{{ number_format($p['line_total'], 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- Rentals -->
                @if(!empty($tools))
                    <div style="margin-top:18px;">
                        <div style="font-size:12px;font-weight:800;letter-spacing:2px;text-transform:uppercase;color:#d1d5db;margin-bottom:10px;">
                            Tool Rentals
                        </div>
                        <div style="border:1px solid #1f2937;border-radius:12px;overflow:hidden;">
                            <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;">
                                <thead>
                                    <tr style="background:#1a1a1a;">
                                        <th align="left" style="padding:10px 12px;font-size:11px;color:#9ca3af;text-transform:uppercase;letter-spacing:1px;border-bottom:1px solid #1f2937;">Tool</th>
                                        <th align="center" style="padding:10px 12px;font-size:11px;color:#9ca3af;text-transform:uppercase;letter-spacing:1px;border-bottom:1px solid #1f2937;">Period</th>
                                        <th align="center" style="padding:10px 12px;font-size:11px;color:#9ca3af;text-transform:uppercase;letter-spacing:1px;border-bottom:1px solid #1f2937;">Qty</th>
                                        <th align="right" style="padding:10px 12px;font-size:11px;color:#9ca3af;text-transform:uppercase;letter-spacing:1px;border-bottom:1px solid #1f2937;">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tools as $t)
                                        <tr>
                                            <td style="padding:10px 12px;border-bottom:1px solid #1f2937;color:#f3f4f6;font-size:13px;">{{ $t['title'] }}</td>
                                            <td align="center" style="padding:10px 12px;border-bottom:1px solid #1f2937;color:#d1d5db;font-size:12px;">
                                                <div style="color:#d1d5db;font-weight:700;">{{ $t['start_date'] }} → {{ $t['due_date'] }}</div>
                                                <div style="color:#fdba74;font-weight:800;margin-top:2px;">{{ (int)$t['days'] }} day{{ (int)$t['days'] > 1 ? 's' : '' }}</div>
                                            </td>
                                            <td align="center" style="padding:10px 12px;border-bottom:1px solid #1f2937;color:#d1d5db;font-size:13px;">{{ (int)$t['quantity'] }}</td>
                                            <td align="right" style="padding:10px 12px;border-bottom:1px solid #1f2937;color:#ffffff;font-weight:800;font-size:13px;">₱{{ number_format($t['line_total'], 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- Totals -->
                <div style="margin-top:18px;display:flex;gap:16px;flex-wrap:wrap;">
                    <div style="flex:1;min-width:240px;background:#0f0f0f;border:1px solid #1f2937;border-radius:12px;padding:14px 16px;">
                        <div style="font-size:12px;font-weight:800;letter-spacing:2px;text-transform:uppercase;color:#9ca3af;margin-bottom:10px;">
                            Payment Summary
                        </div>
                        <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;font-size:13px;">
                            <tr>
                                <td style="padding:6px 0;color:#9ca3af;">Grand Total</td>
                                <td align="right" style="padding:6px 0;color:#ffffff;font-weight:900;">₱{{ number_format($grandTotal, 2) }}</td>
                            </tr>
                            <tr>
                                <td style="padding:6px 0;color:#9ca3af;">Amount Paid</td>
                                <td align="right" style="padding:6px 0;color:#d1d5db;font-weight:800;">₱{{ number_format($amountPaid, 2) }}</td>
                            </tr>
                            <tr>
                                <td style="padding:6px 0;color:#9ca3af;">Change</td>
                                <td align="right" style="padding:6px 0;color:#a7f3d0;font-weight:900;">₱{{ number_format($change, 2) }}</td>
                            </tr>
                        </table>
                    </div>

                    <div style="flex:1;min-width:240px;border:1px dashed rgba(249,115,22,0.45);border-radius:12px;padding:14px 16px;background:rgba(249,115,22,0.06);">
                        <div style="font-size:12px;font-weight:800;letter-spacing:2px;text-transform:uppercase;color:#fdba74;margin-bottom:8px;">
                            Need help?
                        </div>
                        <div style="font-size:13px;color:#d1d5db;line-height:1.6;">
                            If you have questions about this receipt, reply to this email and include your receipt number:
                            <span style="color:#ffffff;font-weight:900;">#{{ $transactionId }}</span>.
                        </div>
                        <div style="margin-top:10px;font-size:12px;color:#9ca3af;">
                            This email includes a PDF and a plain-text receipt attachment for your records.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div style="padding:16px 22px;border-top:1px solid #1f2937;background:#0f0f0f;">
                <div style="font-size:12px;color:#6b7280;line-height:1.6;">
                    © {{ date('Y') }} BarliTor Shop. All rights reserved.<br>
                    This is an automated message — please do not share sensitive information via email.
                </div>
            </div>
        </div>
    </div>
</body>
</html>

