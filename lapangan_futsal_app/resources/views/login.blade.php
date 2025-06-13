<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="Sat, 01 Jan 2000 00:00:00 GMT">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="/css/login.css">
    <script>
        if (performance.navigation.type === 2) {
            // Jika user menekan back/forward, reload dari server
            location.reload(true);
        }
    </script>
</head>
<body>
    @if(session('admin_id'))
        <script>window.location.href = '/dashboard';</script>
    @endif
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
        <a href="/" class="login-home-btn" style="display:inline-block;margin-top:18px;text-align:center;background:#0056b3;color:#fff;padding:10px 28px;border-radius:8px;font-weight:600;text-decoration:none;transition:background 0.2s;">Ke Beranda</a>
    </div>
</body>
</html> 