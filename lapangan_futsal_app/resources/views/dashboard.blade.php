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
            <div class="sidebar-title">DASHBOARD</div>
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
            @if(isset($lapangan) && count($lapangan) > 0)
            <div class="lapangan-list-rows">
                @foreach($lapangan as $index => $l)
                <div class="lapangan-row-box">
                    <div class="lapangan-row-img">
                        @if($l->gambar)
                            @php
                                $isPng = substr($l->gambar, 0, 8) === "\x89PNG\r\n\x1a\n";
                                $mime = $isPng ? 'image/png' : 'image/jpeg';
                            @endphp
                            <img src="data:{{ $mime }};base64,{{ base64_encode($l->gambar) }}" alt="Gambar" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            <div class="lapangan-img-placeholder">Lapangan {{ $index+1 }}</div>
                        @endif
                    </div>
                    <div class="lapangan-row-info">
                        <div class="lapangan-row-title">Lapangan {{ $index+1 }}</div>
                        @php
                            $jamList = [
                                '10.00 - 11.00', '11.00 - 12.00', '12.00 - 13.00', '13.00 - 14.00', '14.00 - 15.00',
                                '15.00 - 16.00', '16.00 - 17.00', '17.00 - 18.00', '18.00 - 19.00', '19.00 - 20.00'
                            ];
                            $statusJam = $l->status_jam ? json_decode($l->status_jam, true) : [];
                            $jamTidakTersedia = [];
                            if (!empty($statusJam)) {
                                foreach ($jamList as $jam) {
                                    if (isset($statusJam[$jam]) && $statusJam[$jam] === 'Tidak tersedia') {
                                        $jamTidakTersedia[] = $jam;
                                    }
                                }
                            } else if (!empty($l->waktu_booking)) {
                                $jamTidakTersedia = array_map('trim', explode(',', $l->waktu_booking));
                            }
                        @endphp
                        <div class="lapangan-row-jam-grid">
                            @foreach($jamList as $jam)
                                <span class="{{ in_array($jam, $jamTidakTersedia) ? 'jam-tidak-tersedia' : 'jam-tersedia' }}">{{ $jam }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var kontenTitle = document.getElementById('konten-title');
        var kontenMenu = document.getElementById('konten-menu');
        kontenTitle.addEventListener('click', function() {
            kontenMenu.style.display = kontenMenu.style.display === 'block' ? 'none' : 'block';
        });
        // Tampilkan form input jika menu Input diklik
        var inputMenu = document.getElementById('menu-input');
        var formInput = document.getElementById('form-input-lapangan');
        if(inputMenu && formInput) {
            inputMenu.addEventListener('click', function() {
                formInput.style.display = 'block';
                if(formEdit) formEdit.style.display = 'none';
            });
        }
        var editMenu = document.getElementById('menu-edit');
        var formEdit = document.getElementById('form-edit-lapangan');
        if(editMenu && formEdit) {
            editMenu.addEventListener('click', function() {
                formEdit.style.display = 'block';
                if(formInput) formInput.style.display = 'none';
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

    function toggleLapanganDetail(box) {
        box.classList.toggle('active');
    }
</script>
</body>
</html> 