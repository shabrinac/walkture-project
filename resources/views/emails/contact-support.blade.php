<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Walkture Support Message</title>
    <style>
        body { background: #f9f9ff; font-family: 'Inter', Arial, sans-serif; color: #141b2b; margin: 0; padding: 0; }
        .wrapper { max-width: 560px; margin: 40px auto; background: #fff; border-radius: 16px; border: 1px solid #e5e7eb; overflow: hidden; }
        .header { background: #43664c; padding: 28px 32px; text-align: center; }
        .header img { width: 36px; height: 36px; }
        .header h1 { color: #fff; font-size: 20px; font-weight: 700; margin: 8px 0 0; }
        .header p { color: rgba(255,255,255,0.7); font-size: 13px; margin: 4px 0 0; }
        .body { padding: 28px 32px; }
        .field-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #727971; margin-bottom: 4px; }
        .field-value { font-size: 14px; color: #141b2b; font-weight: 500; }
        .divider { border: 0; border-top: 1px solid #f1f3ff; margin: 18px 0; }
        .message-box { background: #f9f9ff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px 18px; font-size: 14px; line-height: 1.7; color: #424842; white-space: pre-wrap; }
        .footer { background: #f1f3ff; padding: 16px 32px; text-align: center; font-size: 11px; color: #727971; }
        .reply-btn { display: inline-block; margin: 16px 0 0; background: #43664c; color: #fff; text-decoration: none; font-size: 13px; font-weight: 700; padding: 10px 24px; border-radius: 10px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>Walkture Support</h1>
            <p>New message from the contact form</p>
        </div>
        <div class="body">
            <div style="margin-bottom:14px">
                <div class="field-label">Subject</div>
                <div class="field-value">{{ $userSubject }}</div>
            </div>
            <hr class="divider">
            <div class="field-label" style="margin-bottom:8px">Message</div>
            <div class="message-box">{{ $messageBody }}</div>
        </div>
        <div class="footer">
            This message was submitted via the Walkture contact form at {{ now()->format('d M Y, H:i') }} WITA.<br>
            © {{ date('Y') }} Walkture — GIS Photo Platform, Samarinda
        </div>
    </div>
</body>
</html>
