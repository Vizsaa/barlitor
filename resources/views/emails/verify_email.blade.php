@php
    /** @var \App\Models\User $user */
    $verifyUrl = url('/verify-email/' . $user->email_verification_token);
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify Your BruTor Shop Account</title>
</head>
<body style="margin:0;padding:0;background-color:#111111;font-family:Arial,Helvetica,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#111111;padding:24px 0;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0" border="0" style="background-color:#1a1a1a;border-radius:8px;overflow:hidden;border:1px solid #333333;">
                <tr>
                    <td align="center" style="padding:24px 24px 16px 24px;background-color:#111111;border-bottom:1px solid #333333;">
                        <div style="font-size:24px;font-weight:bold;color:#ffffff;">
                            BruTor <span style="color:#f97316;">Shop</span>
                        </div>
                        <div style="margin-top:4px;font-size:12px;color:#9ca3af;">
                            Automotive Parts &amp; Tool Rentals
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="padding:24px 24px 8px 24px;color:#e5e7eb;font-size:14px;line-height:1.6;">
                        <p style="margin:0 0 12px 0;">Hi {{ $user->name }},</p>
                        <p style="margin:0 0 12px 0;">
                            Thank you for registering at <strong>BruTor Shop</strong>. Please click the button below to verify
                            your email address and activate your account.
                        </p>
                    </td>
                </tr>
                <tr>
                    <td align="center" style="padding:8px 24px 24px 24px;">
                        <a href="{{ $verifyUrl }}"
                           style="display:inline-block;padding:12px 28px;background-color:#f97316;color:#ffffff;text-decoration:none;
                                  border-radius:999px;font-size:14px;font-weight:bold;">
                            Verify My Account
                        </a>
                    </td>
                </tr>
                <tr>
                    <td style="padding:0 24px 16px 24px;color:#9ca3af;font-size:12px;line-height:1.6;">
                        <p style="margin:0 0 10px 0;">
                            If the button above does not work, copy and paste this link into your browser:
                        </p>
                        <p style="margin:0 0 10px 0;word-break:break-all;color:#d1d5db;">
                            <a href="{{ $verifyUrl }}" style="color:#f97316;text-decoration:none;">{{ $verifyUrl }}</a>
                        </p>
                        <p style="margin:0 0 10px 0;">
                            If you did not create an account, you can safely ignore this email.
                        </p>
                        <p style="margin:0;">
                            This link will remain active until you verify your account.
                        </p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:16px 24px 20px 24px;background-color:#111111;border-top:1px solid #333333;color:#6b7280;font-size:11px;text-align:center;">
                        &copy; {{ date('Y') }} BruTor Shop. All rights reserved.
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>

