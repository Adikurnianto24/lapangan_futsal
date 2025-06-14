<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Lapangan</title>
    <link rel="stylesheet" href="/css/beranda.css">
    <link rel="stylesheet" href="/css/detail_lapangan.css">
</head>
<body>
    <div class="header">
        <div class="brand">ARJUNA<br>FUTSAL</div>
        <a href="/" class="login-link">BERANDA</a>
    </div>
    <div class="detail-container">
        @if($lapangan)
            <div class="top-title-and-content-row">
                <div class="main-title"><span style="font-weight:bold">LAPANGAN {{ $nomor_lapangan }}</span></div>
                <div class="image-text-wrapper">
                    <div class="detail-image-wrapper">
                        <div class="detail-image">
                            @if($lapangan->gambar)
                                @php
                                    $isPng = substr($lapangan->gambar, 0, 8) === "\x89PNG\r\n\x1a\n";
                                    $mime = $isPng ? 'image/png' : 'image/jpeg';
                                @endphp
                                <img src="data:{{ $mime }};base64,{{ base64_encode($lapangan->gambar) }}" alt="Gambar Lapangan">
                            @else
                                <div class="placeholder">Tidak ada gambar</div>
                            @endif
                        </div>
                    </div>
                    <div class="text-details-wrapper">
                        <div class="lapangan-name">{{ $lapangan->nama_lapangan }}</div>
                        <div class="detail-description">
                            <p>{{ $lapangan->deskripsi_lapangan }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @php
                $jamList = [
                    '10.00 - 11.00', '11.00 - 12.00', '12.00 - 13.00', '13.00 - 14.00', '14.00 - 15.00',
                    '15.00 - 16.00', '16.00 - 17.00', '17.00 - 18.00', '18.00 - 19.00', '19.00 - 20.00'
                ];
                $statusJam = $lapangan->status_jam ? json_decode($lapangan->status_jam, true) : [];
                $jamTidakTersedia = [];
                if (!empty($statusJam)) {
                    foreach ($jamList as $jam) {
                        if (isset($statusJam[$jam]) && $statusJam[$jam] === 'Tidak tersedia') {
                            $jamTidakTersedia[] = $jam;
                        }
                    }
                } else if (!empty($lapangan->waktu_booking)) {
                    $jamTidakTersedia = array_map('trim', explode(',', $lapangan->waktu_booking));
                }
            @endphp

            <form id="jam-form">
                <div class="jam-list-container" id="jam-list-1">
                    @foreach(array_slice($jamList,0,5) as $jam)
                        @if(!in_array($jam, $jamTidakTersedia))
                            <span class="jam-tersedia selectable-jam" data-jam="{{ $jam }}">{{ $jam }}</span>
                        @else
                            <span class="jam-tidak-tersedia">{{ $jam }}</span>
                        @endif
                    @endforeach
                </div>
                <div class="jam-list-container" id="jam-list-2">
                    @foreach(array_slice($jamList,5,5) as $jam)
                        @if(!in_array($jam, $jamTidakTersedia))
                            <span class="jam-tersedia selectable-jam" data-jam="{{ $jam }}">{{ $jam }}</span>
                        @else
                            <span class="jam-tidak-tersedia">{{ $jam }}</span>
                        @endif
                    @endforeach
                </div>
            </form>

            <a id="wa-link" href="#" target="_blank" style="text-decoration:none;">
                <button class="pesan-btn">Pesan Sekarang</button>
            </a>
        @else
            <p>Lapangan tidak ditemukan.</p>
        @endif
    </div>

    <style>
        .jam-list-container {
            display: flex;
            flex-direction: row;
            gap: 10px;
            margin-bottom: 10px;
        }
        .selectable-jam.selected {
            background: #4CAF50;
            color: #fff;
            border-radius: 5px;
            padding: 2px 8px;
        }
        .selectable-jam {
            cursor: pointer;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle select jam
            let selectedJam = null;
            document.querySelectorAll('.selectable-jam').forEach(function(el) {
                el.addEventListener('click', function() {
                    document.querySelectorAll('.selectable-jam').forEach(function(span) {
                        span.classList.remove('selected');
                    });
                    el.classList.add('selected');
                    selectedJam = el.getAttribute('data-jam');
                });
            });
            // Handle WA button
            const btn = document.getElementById('wa-link');
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const namaLapangan = @json($lapangan->nama_lapangan ?? '');
                const nomorLapangan = @json($nomor_lapangan ?? '');
                if (!selectedJam) {
                    alert('Silakan pilih jam booking terlebih dahulu!');
                    return;
                }
                const pesan = encodeURIComponent(
                    `Halo, saya ingin booking Lapangan ${nomorLapangan} (${namaLapangan}) untuk jam ${selectedJam}.`
                );
                const waUrl = `https://wa.me/6285893110319?text=${pesan}`;
                window.open(waUrl, '_blank');
            });
        });
    </script>
</body>
</html> 