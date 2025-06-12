<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="/css/login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-title">Arjuna Futsal</div>
        <div class="login-subtitle">Salam Sehat!</div>
        <form class="login-form" method="POST" action="/login">
            @csrf
            @if($errors->has('login'))
                <div style="color:red; margin-bottom:10px;">{{ $errors->first('login') }}</div>
            @endif
            <div class="form-group">
                <label for="username">Nama Pengguna</label>
                <input type="text" id="username" name="username" autocomplete="off">
            </div>
            <div class="form-group">
                <label for="password">Kata Sandi</label>
                <input type="password" id="password" name="password">
            </div>
            <button type="submit" class="login-btn">Masuk</button>
        </form>
    </div>
</body>
</html> 