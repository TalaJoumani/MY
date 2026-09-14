<!DOCTYPE html>
<html dir="ltr" lang="en">
<body style="font-family: sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; background-color: #f4f6f4;">

    <!-- رأسية الإيميل بلون العيادات الأخضر مع الشعار -->
    <div style="text-align: center; margin-bottom: 20px; background-color: #1b5e3b; padding: 25px; border-radius: 8px 8px 0 0;">
        <img src="{{ $message->embed(public_path('images/my.jpg')) }}" alt="My Clinics Logo" style="width: 140px; height: auto;">
    </div>

    <!-- محتوى الإيميل الأبيض مع لمسات ذهبية/خضراء مطابقة للوجو -->
    <div style="background-color: #ffffff; padding: 30px; border-radius: 0 0 8px 8px; border: 1px solid #d4ebd0; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        <h2 style="color: #1b5e3b; margin-top: 0;">Welcome, {{ $user->first_name }} {{ $user->last_name }}</h2>
        <p>Your account has been successfully created in our system. You can now log in using the following credentials:</p>

        <!-- صندوق البيانات (الإيميل وكلمة المرور) -->
        <div style="background: #f9fbf9; padding: 20px; border-radius: 8px; border-left: 5px solid #d4af37; border-right: none; margin: 20px 0; border: 1px solid #e2ece0;">
            <p style="margin: 5px 0;"><strong>Email:</strong> <span style="color: #333;">{{ $user->email }}</span></p>
            <p style="margin: 5px 0;"><strong>Password:</strong> <span style="color: #d4af37; font-weight: bold; font-size: 18px; letter-spacing: 1px;">{{ $password }}</span></p>
        </div>

        <p style="color: #555;">We are glad to have you with us!</p>
    </div>

    <!-- تذييل الإيميل -->
    <div style="text-align: center; margin-top: 20px; font-size: 12px; color: #777;">
        <p>Best regards,<br><strong style="color: #1b5e3b;">MY CLINICS Management</strong></p>
    </div>

</body>
</html>