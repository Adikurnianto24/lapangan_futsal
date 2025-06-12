<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="Sat, 01 Jan 2000 00:00:00 GMT">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="/css/dashboard.css">
</head>
<body>
<div class="container">
    <div class="sidebar">
        <div class="sidebar-top">
            <div class="sidebar-title"><a href="/dashboard" style="text-decoration:none;color:inherit;">DASHBOARD</a></div>
            <div class="sidebar-menu">
                <div class="menu-section">
                    <div class="menu-section-title" id="konten-title" style="cursor:pointer;">Manajemen Konten</div>
                    <ul id="konten-menu" style="display:none;">
                        <li><a href="{{ route('dashboard.input') }}">Input</a></li>
                        <li><a href="{{ route('dashboard.edit') }}">Edit</a></li>
                        <li><a href="{{ route('dashboard.delete') }}">Delete</a></li>
                    </ul>
                </div>
                <div class="menu-section">
                    <div class="menu-section-title"><a href="{{ route('dashboard.nota') }}" style="color:inherit;text-decoration:none;">Nota</a></div>
                </div>
            </div>
        </div>
        <div class="sidebar-bottom">
            <form method="POST" action="/logout">
                @csrf
                <button type="submit" class="logout-btn">Keluar</button>
            </form>
        </div>
    </div>
    <div class="main">
        <div class="header">Manajemen Lapangan</div>
        <div class="content">
            @yield('content')
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var kontenTitle = document.getElementById('konten-title');
        var kontenMenu = document.getElementById('konten-menu');
        if(kontenTitle && kontenMenu) {
            kontenTitle.addEventListener('click', function() {
                kontenMenu.style.display = kontenMenu.style.display === 'block' ? 'none' : 'block';
            });
        }
        // Upload gambar pada form input
        var inputGambarBtn = document.getElementById('input-gambar-btn');
        var inputGambarFile = document.getElementById('input-gambar-file');
        var inputGambarText = document.getElementById('input-gambar-text');
        if(inputGambarBtn && inputGambarFile && inputGambarText) {
            inputGambarBtn.addEventListener('click', function() {
                inputGambarFile.click();
            });
            inputGambarFile.addEventListener('change', function(e) {
                if (inputGambarFile.files.length > 0) {
                    inputGambarText.textContent = inputGambarFile.files[0].name;
                } else {
                    inputGambarText.textContent = 'Text';
                }
            });
        }
    });
</script>
</body>
</html> 