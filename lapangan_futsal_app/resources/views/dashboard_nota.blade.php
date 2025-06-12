@extends('layouts.dashboard')
@section('content')
<link rel="stylesheet" href="/css/dashboard_nota.css">
<div class="tambah-nota-btn-wrapper">
    <a href="#" class="tambah-nota-btn" id="showNotaFormBtn">+ Tambah Nota</a>
</div>

{{-- Card Widget Nota --}}
@if(isset($nota) && count($nota) > 0)
    <div class="nota-list-rows">
        @foreach($nota as $n)
            <div class="nota-card">
                <div class="nota-card-img">
                    @if($n->lapangan && $n->lapangan->gambar)
                        @php
                            $isPng = substr($n->lapangan->gambar, 0, 8) === "\x89PNG\r\n\x1a\n";
                            $mime = $isPng ? 'image/png' : 'image/jpeg';
                        @endphp
                        <img src="data:{{ $mime }};base64,{{ base64_encode($n->lapangan->gambar) }}" alt="Gambar Lapangan" class="nota-img">
                    @else
                        <div class="nota-img-placeholder">No Image</div>
                    @endif
                </div>
                <div class="nota-card-info">
                    <div class="nota-card-title">{{ $n->lapangan ? $n->lapangan->nama_lapangan : '-' }}</div>
                    <div class="nota-card-meta">{{ $n->nama_pemesan }} | {{ $n->kontak_pemesan }} | {{ $n->waktu_pemesanan }}</div>
                </div>
                <div class="nota-card-action">
                    <a href="#" class="ubah-nota-btn"
                       data-id="{{ $n->id }}"
                       data-nama="{{ $n->nama_pemesan }}"
                       data-kontak="{{ $n->kontak_pemesan }}"
                       data-waktu="{{ $n->waktu_pemesanan }}">Ubah</a>
                    <a href="#">Hapus</a>
                </div>
            </div>
        @endforeach
    </div>
@endif

<div class="modal-overlay" id="modalOverlay" style="display:none;"></div>
<div class="modal-form" id="modalForm" style="display:none;">
    <form class="tambah-nota-form" method="POST" action="{{ route('nota.store') }}">
        @csrf
        <div class="input-group" id="lapangan-group">
            <label for="lapangan">Lapangan</label>
            <select id="lapangan" name="lapangan" class="input-field">
                <option value="" selected disabled>Pilih Lapangan...</option>
                {{-- Loop data lapangan jika ada --}}
                @if(isset($lapangan) && count($lapangan) > 0)
                    @foreach($lapangan as $l)
                        <option value="{{ $l->id_lapangan }}">{{ $l->nama_lapangan }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="input-group">
            <label for="nama_pemesan">Nama Pemesan</label>
            <input type="text" id="nama_pemesan" name="nama_pemesan" class="input-field">
        </div>
        <div class="input-group">
            <label for="no_telepon">No Telepon</label>
            <input type="text" id="no_telepon" name="no_telepon" class="input-field">
        </div>
        <div class="input-group">
            <label for="waktu_booking">Waktu Booking</label>
            <select id="waktu_booking" name="waktu_booking" class="input-field">
                <option value="" selected disabled>Pilih Waktu Booking...</option>
                <option value="10.00 - 11.00">10.00 - 11.00</option>
                <option value="11.00 - 12.00">11.00 - 12.00</option>
                <option value="12.00 - 13.00">12.00 - 13.00</option>
                <option value="13.00 - 14.00">13.00 - 14.00</option>
                <option value="14.00 - 15.00">14.00 - 15.00</option>
                <option value="15.00 - 16.00">15.00 - 16.00</option>
                <option value="16.00 - 17.00">16.00 - 17.00</option>
                <option value="17.00 - 18.00">17.00 - 18.00</option>
                <option value="18.00 - 19.00">18.00 - 19.00</option>
                <option value="19.00 - 20.00">19.00 - 20.00</option>
            </select>
        </div>
        <div style="display:flex;justify-content:center;margin-top:18px;">
            <button type="submit" class="simpan-btn">Simpan</button>
        </div>
    </form>
</div>
<script>
    document.getElementById('showNotaFormBtn').onclick = function(e) {
        e.preventDefault();
        document.getElementById('modalOverlay').style.display = 'block';
        document.getElementById('modalForm').style.display = 'block';
        // Reset form for add
        document.querySelector('.tambah-nota-form').reset();
        document.querySelector('.tambah-nota-form').action = "{{ route('nota.store') }}";
        document.querySelector('.tambah-nota-form').removeAttribute('data-edit');
        // Show Lapangan field
        document.getElementById('lapangan-group').style.display = 'block';
    };
    document.getElementById('modalOverlay').onclick = function() {
        document.getElementById('modalOverlay').style.display = 'none';
        document.getElementById('modalForm').style.display = 'none';
    };
    // Ubah button logic
    document.querySelectorAll('.ubah-nota-btn').forEach(function(btn) {
        btn.onclick = function(e) {
            e.preventDefault();
            document.getElementById('modalOverlay').style.display = 'block';
            document.getElementById('modalForm').style.display = 'block';
            // Fill form fields
            document.getElementById('nama_pemesan').value = this.getAttribute('data-nama');
            document.getElementById('no_telepon').value = this.getAttribute('data-kontak');
            document.getElementById('waktu_booking').value = this.getAttribute('data-waktu');
            // Hide Lapangan field
            document.getElementById('lapangan-group').style.display = 'none';
            // Set form action for update (assume route: nota.update, method POST with _method PATCH)
            var form = document.querySelector('.tambah-nota-form');
            form.action = '/nota/' + this.getAttribute('data-id');
            // Add hidden _method input for PATCH
            if (!form.querySelector('input[name="_method"]')) {
                var methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PATCH';
                form.appendChild(methodInput);
            } else {
                form.querySelector('input[name="_method"]').value = 'PATCH';
            }
            form.setAttribute('data-edit', 'true');
        };
    });
    // On modal close, remove _method if not editing
    document.getElementById('modalOverlay').addEventListener('click', function() {
        var form = document.querySelector('.tambah-nota-form');
        if (!form.getAttribute('data-edit')) {
            var methodInput = form.querySelector('input[name="_method"]');
            if (methodInput) methodInput.remove();
        }
    });
</script>
@endsection 