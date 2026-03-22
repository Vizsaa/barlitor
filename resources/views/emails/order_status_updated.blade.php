<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status Updated</title>
</head>
<body style="margin:0;padding:0;background:#0b0b0b;font-family:Arial, Helvetica, sans-serif;color:#e5e7eb;">
    <div style="width:100%;padding:24px 12px;background:#0b0b0b;">
        <div style="max-width:720px;margin:0 auto;background:#111111;border:1px solid #1f2937;border-radius:16px;overflow:hidden;">
            <div style="padding:18px 22px;border-bottom:1px solid #1f2937;background:#0f0f0f;">
                <div style="font-size:14px;letter-spacing:2px;text-transform:uppercase;color:#9ca3af;font-weight:700;">
                    BarliTor Shop
                </div>
                <div style="font-size:20px;font-weight:900;color:#ffffff;margin-top:6px;">
                    Your order status was updated
                </div>
                <div style="margin-top:8px;font-size:12px;color:#9ca3af;">
                    Order #: <span style="color:#fde68a;font-weight:800;">{{ $order->orderinfo_id }}</span>
                    <span style="color:#374151;padding:0 8px;">•</span>
                    Order date: <span style="color:#e5e7eb;font-weight:700;">{{ $order->date_placed }}</span>
                </div>
            </div>

            <div style="padding:22px;">
                <div style="font-size:13px;color:#d1d5db;line-height:1.6;">
                    Status changed from
                    <span style="font-weight:900;color:#ffffff;">{{ $oldStatus }}</span>
                    to
                    <span style="font-weight:900;color:#fdba74;">{{ $newStatus }}</span>.
                </div>

                <div style="margin-top:14px;padding:14px 16px;border-radius:12px;background:#0f0f0f;border:1px solid #1f2937;">
                    <div style="font-size:12px;font-weight:800;letter-spacing:2px;text-transform:uppercase;color:#9ca3af;margin-bottom:8px;">
                        Quick links
                    </div>
                    <a href="{{ route('orders.mine') }}"
                       style="display:inline-block;text-decoration:none;background:#f97316;color:#ffffff;font-weight:900;font-size:12px;letter-spacing:1px;text-transform:uppercase;padding:10px 14px;border-radius:12px;border:1px solid #ea580c;">
                        View My Orders
                    </a>
                </div>
            </div>

            <div style="padding:16px 22px;border-top:1px solid #1f2937;background:#0f0f0f;">
                <div style="font-size:12px;color:#6b7280;line-height:1.6;">
                    © {{ date('Y') }} BarliTor Shop. All rights reserved.
                </div>
            </div>
        </div>
    </div>
</body>
</html>

