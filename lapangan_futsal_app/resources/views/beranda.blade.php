<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda</title>
    <link rel="stylesheet" href="/css/beranda.css">
    <style>
        html, body {
            overflow-x: hidden !important;
        }
        .main-box {
            overflow-x: hidden !important;
        }
        .lapangan-scroll {
            overflow-x: hidden !important;
            overflow: visible !important;
        }
        .lapangan-scroll::-webkit-scrollbar {
            display: none !important;
            width: 0 !important;
            height: 0 !important;
            background: transparent !important;
        }
    </style>
    <!-- <style> carousel css dipindah ke beranda.css -->
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
            <button class="carousel-btn left" id="carouselLeft" aria-label="Sebelumnya">
                <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M21 16H11M11 16L16 11M11 16L16 21" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
            <div class="lapangan-scroll" id="lapanganScroll" style="overflow:visible;gap:0;justify-content:center;">
                @if(isset($lapangan) && count($lapangan) > 0)
                    @foreach($lapangan as $index => $l)
                        <a href="/lapangan/{{ $l->id_lapangan }}" class="lapangan-link lapangan-carousel-item" data-index="{{ $index }}" style="display:{{ $index === 0 ? 'block' : 'none' }};">
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
            <button class="carousel-btn right" id="carouselRight" aria-label="Berikutnya">
                <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M11 16H21M21 16L16 11M21 16L16 21" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>
        <div class="pesan-btn-wrapper">
            <a href="https://wa.me/6285893110319" target="_blank" style="text-decoration:none;">
                <button class="pesan-btn">Pesan Sekarang</button>
            </a>
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
            const items = Array.from(document.querySelectorAll('.lapangan-carousel-item'));
            let current = 0;
            function showItems(idx) {
                const n = items.length;
                items.forEach((el, i) => {
                    el.classList.remove('active', 'prev', 'next');
                    el.style.display = 'none';
                });
                if (n === 0) return;
                if (n === 1) {
                    items[0].classList.add('active');
                    items[0].style.display = 'block';
                    return;
                }
                if (n === 2) {
                    items[idx].classList.add('active');
                    items[idx].style.display = 'block';
                    const prev = (idx - 1 + n) % n;
                    items[prev].classList.add('prev');
                    items[prev].style.display = 'block';
                    return;
                }
                // Untuk 3 lapangan atau lebih
                const prev = (idx - 1 + n) % n;
                const next = (idx + 1) % n;
                // Tampilkan prev di kiri, active di tengah, next di kanan
                items.forEach((el, i) => {
                    if (i === prev) {
                        el.classList.add('prev');
                        el.style.display = 'block';
                    } else if (i === idx) {
                        el.classList.add('active');
                        el.style.display = 'block';
                    } else if (i === next) {
                        el.classList.add('next');
                        el.style.display = 'block';
                    } else {
                        el.style.display = 'none';
                    }
                });
                items[prev].classList.add('prev');
                items[prev].style.display = 'block';
                items[idx].classList.add('active');
                items[idx].style.display = 'block';
                items[next].classList.add('next');
                items[next].style.display = 'block';
            }
            showItems(current);
            document.getElementById('carouselLeft').onclick = function() {
                current = (current - 1 + items.length) % items.length;
                showItems(current);
            };
            document.getElementById('carouselRight').onclick = function() {
                current = (current + 1) % items.length;
                showItems(current);
            };
        });
    </script>
</body>
</html>