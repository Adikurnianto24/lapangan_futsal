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
                $jamTidakTersedia = $lapangan->waktu_booking ? array_map('trim', explode(',', $lapangan->waktu_booking)) : [];
            @endphp

            <div class="jam-list-container">
                @foreach(array_slice($jamList,0,5) as $jam)
                    <span class="{{ in_array($jam, $jamTidakTersedia) ? 'jam-tidak-tersedia' : 'jam-tersedia' }}">
                        {{ $jam }}
                    </span>
                @endforeach
            </div>
            <div class="jam-list-container">
                @foreach(array_slice($jamList,5,5) as $jam)
                    <span class="{{ in_array($jam, $jamTidakTersedia) ? 'jam-tidak-tersedia' : 'jam-tersedia' }}">
                        {{ $jam }}
                    </span>
                @endforeach
            </div>

            <button class="pesan-btn">Pesan Sekarang</button>
        @else
            <p>Lapangan tidak ditemukan.</p>
        @endif
    </div>
</body>
</html> 