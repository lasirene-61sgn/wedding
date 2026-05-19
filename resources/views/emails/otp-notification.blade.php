<!DOCTYPE html>
<html>
<head>
    <style>
        .otp-container {
            font-family: Arial, sans-serif;
            padding: 20px;
            border: 1px solid #eee;
            border-radius: 10px;
            text-align: center;
        }
        .otp-code {
            font-size: 32px;
            font-weight: bold;
            color: #ff4757; /* A wedding-style reddish color */
            letter-spacing: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="otp-container">
        <h2>Password Reset Request</h2>
        <p>Hello,</p>
        <p>You requested an OTP to reset your password for the **Happy Wedding** portal. Use the code below:</p>
        
        <div class="otp-code">{{ $otp }}</div>
        
        <p>This code will expire in 10 minutes.</p>
        <p>If you did not request this, please ignore this email.</p>
    </div>
</body>
</html>