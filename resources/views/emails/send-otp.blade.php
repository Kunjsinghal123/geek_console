<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In Verification</title>

</head>

<body style="margin:0; padding:0; background-color:#f4f4f7; font-family: Roboto, Arial, Helvetica, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td align="center">

                <!-- MAIN CONTAINER -->
                <table width="600" cellpadding="0" cellspacing="0" border="0" style="background:#ffffff; margin:40px 0; padding:30px; border:1px solid #7c4dff; border-radius:8px;">

                    <!-- LOGO -->
                    <tr>
                        <td align="center" style="padding-bottom:10px;">
                            <a href="{{ route('login') }}" style="text-decoration:none; display:inline-block;">
                                <img src="{{ asset('assets/images/brand-logos/desktop-logo.png') }}" alt="logo">
                            </a>
                        </td>
                    </tr>

                    <!-- TITLE -->
                    <tr>
                        <td align="center">
                            <h2 style="margin:10px 0 20px; color:#1a1a1a;">Verify Your Sign In Request</h2>
                        </td>
                    </tr>

                    <!-- DIVIDER -->
                    <tr>
                        <td>
                            <hr style="border:none; border-top:1px solid #7c4dff;">
                        </td>
                    </tr>

                    <!-- CONTENT -->
                    <tr>
                        <td style="color:#333; font-size:14px; line-height:1.6; padding-top:15px;">

                            <p><strong>Hello {{ $storeName }},</strong></p>

                            <p>We detected a sign-in attempt to your <strong>Geek Search Console</strong> account from a new device or location. To ensure the security of your account, please use the One-Time Password (OTP) below to complete your verification.</p>

                            <p>Verification Code:</p>

                            <p style="text-align:center; font-size:35px; letter-spacing:2px; margin:20px 0; color:#000;">
                                {{ $otp }}
                            </p>

                            <p>This code will expire in <strong>5 minutes</strong>.</p>
                            
                            <p>For your security, please do not share this code with anyone.</p>

                            <p>If you did not initiate this request, please disregard this message and consider reviewing your account security.</p>

                            <p>Thank you for your attention.</p>

                            <p style="margin-top:20px;">
                                Sincerely,<br>
                                <strong>Geek Team</strong>
                            </p>

                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>