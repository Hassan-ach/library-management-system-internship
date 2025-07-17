<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <p>Hello, {{$user->name}}</p>
    <p>You are receiving this email because we received a password reset request for your account.</p>
    <p>Click the link below to reset your password:</p>
    <a href="{{$url}}">Reset Password</a>
    <p>If you did not request a password reset, you can safely ignore this email.</p>
</body>
</html>
