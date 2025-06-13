@extends('layouts.dashboard')
@section('content')
<div style="display:flex;justify-content:center;width:100%;margin-top:32px;">
    <div class="content-card" style="width:100%;max-width:900px;">
    <form method="POST" action="{{ route('lapangan.store') }}" enctype="multipart/form-data" style="width:100%;">
        @csrf
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
            <div style="font-size: 1.05rem; font-weight: 500;">Memasukkan Gambar</div>
            <button type="submit" class="tambah-btn">Tambah Lapangan</button>
        </div>
        <div class="input-group input-gambar-group">
            <input type="file" id="input-gambar-file" name="gambar" style="display:none;">
            <button type="button" class="gambar-btn" id="input-gambar-btn">Pilih Gambar</button>
            <div class="gambar-text" id="input-gambar-text"></div>
        </div>
        <div class="input-group">
            <label>Judul</label>
            <input type="text" class="input-field" name="judul">
        </div>
        <div class="input-group">
            <label>Deskripsi</label>
            <input type="text" class="input-field" name="deskripsi">
        </div>
    </form>
    </div>
</div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil ditambahkan',
            text: '',
            confirmButtonText: 'OK'
        });
    });
</script>
@endif 