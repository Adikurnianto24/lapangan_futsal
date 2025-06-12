<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda</title>
    <link rel="stylesheet" href="/css/beranda.css">
</head>
<body>
    <div class="header">
        <div class="brand">
            <img src="{{ asset('images/anime_bola.png') }}" alt="Logo" class="brand-logo">
            <span>
                ARJUNA<br>FUTSAL
            </span>
        </div>
        <a href="/login" class="login-link">LOGIN</a>
    </div>
    <div class="main-box">
        <div class="carousel-wrapper">
            <button class="carousel-btn left" id="carouselLeft" aria-label="Sebelumnya">&#8592;</button>
            <div class="lapangan-scroll" id="lapanganScroll">
                @if(isset($lapangan) && count($lapangan) > 0)
                    @foreach($lapangan as $index => $l)
                        <a href="/lapangan/{{ $l->id_lapangan }}" class="lapangan-link">
                            <div class="lapangan-widget">
                                <div class="lapangan-default-title">LAPANGAN {{ $index + 1 }}</div>
                                <div class="lapangan-img">
                                    @if($l->gambar)
                                        @php
                                            $isPng = substr($l->gambar, 0, 8) === "\x89PNG\r\n\x1a\n";
                                            $mime = $isPng ? 'image/png' : 'image/jpeg';
                                        @endphp
                                        <img src="data:{{ $mime }};base64,{{ base64_encode($l->gambar) }}" alt="Gambar" class="img-lapangan">
                                    @else
                                        <div class="lapangan-img-placeholder"></div>
                                    @endif
                                </div>
                                <div class="lapangan-title">{{ $l->nama_lapangan }}</div>
                            </div>
                        </a>
                    @endforeach
                @endif
            </div>
            <button class="carousel-btn right" id="carouselRight" aria-label="Berikutnya">&#8594;</button>
        </div>
        <div class="pesan-btn-wrapper">
            <button class="pesan-btn">Pesan Sekarang</button>
        </div>
    </div>
    <div class="footer">
        <div class="footer-left">
            <img src="{{ asset('images/maps_logo.png') }}" alt="Maps" class="footer-maps-logo">
            <span>Lihat lokasi kami disini<br><a href="https://goo.gl/maps/8Qw6Qw2Qw2Qw2Qw2A" target="_blank" class="footer-link">Lihat disini!</a></span>
        </div>
        <div class="footer-center">Anda Sehat, Kami Bisa Makan!</div>
        <div class="footer-right">
            <div><img src="{{ asset('images/x_logo.png') }}" alt="X" class="footer-x-logo">@ArjunaFutsal.id</div>
            <div><img src="{{ asset('images/ig_logo.png') }}" alt="Instagram" class="footer-ig-logo">@ArjunaFutsalSehat</div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const scrollContainer = document.getElementById('lapanganScroll');
            const btnLeft = document.getElementById('carouselLeft');
            const btnRight = document.getElementById('carouselRight');
            if (btnLeft && btnRight && scrollContainer) {
                btnLeft.addEventListener('click', function() {
                    scrollContainer.scrollBy({ left: -scrollContainer.offsetWidth * 0.8, behavior: 'smooth' });
                });
                btnRight.addEventListener('click', function() {
                    scrollContainer.scrollBy({ left: scrollContainer.offsetWidth * 0.8, behavior: 'smooth' });
                });
            }
        });
    </script>
</body>
</html> 