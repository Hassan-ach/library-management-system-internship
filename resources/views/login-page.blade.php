<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    @if(session('error'))
        <p style="color:red">{{ session('error') }}</p>
    @endif

    <form method="POST" action="{{ route('login-api') }}">
        @csrf
        <label>Email:</label>
        <input type="email" name="email" required><br>

        <label>Password:</label>
        <input type="password" name="password" required><br>

        <button type="submit">Login</button>
    </form>
</body>
</html>

