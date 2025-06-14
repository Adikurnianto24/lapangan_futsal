@extends('layouts.dashboard')
@section('content')
@php
    $jamList = [
        '10.00 - 11.00', '11.00 - 12.00', '12.00 - 13.00', '13.00 - 14.00', '14.00 - 15.00',
        '15.00 - 16.00', '16.00 - 17.00', '17.00 - 18.00', '18.00 - 19.00', '19.00 - 20.00'
    ];
@endphp
<div style="display:flex;justify-content:center;align-items:flex-start;width:100%;min-height:600px;">
    <div class="content-card" style="width:100%;max-width:400px;margin:40px auto 0 auto;display:flex;flex-direction:column;align-items:center;">
        <div class="edit-dropdown-row" style="width:100%;margin-bottom:20px;position:relative;">
            <div class="edit-dropdown" id="editDropdown" style="cursor:pointer;width:100%;">Pilih konten yang ingin di edit <span class="edit-dropdown-arrow" id="editDropdownArrow">&#9660;</span></div>
            <ul id="lapanganDropdownMenu" style="display:none;position:absolute;top:100%;left:0;width:100%;background:#fff;border:2px solid #ccc;border-top:none;z-index:10;list-style:none;padding:0;margin:0;max-height:200px;overflow-y:auto;">
                @foreach($lapangan as $l)
                    <li class="lapangan-dropdown-item" data-id="{{ $l->id_lapangan }}" data-gambar="{{ $l->gambar ? base64_encode($l->gambar) : '' }}" data-nama="{{ $l->nama_lapangan }}" data-deskripsi="{{ $l->deskripsi_lapangan }}" data-status="{{ $l->status }}" data-waktu_booking="{{ $l->waktu_booking }}" data-status_jam="{{ $l->status_jam }}" style="padding:12px 18px;cursor:pointer;border-bottom:1px solid #eee;">{{ $l->nama_lapangan }}</li>
                @endforeach
            </ul>
        </div>
        <div class="edit-img-row" style="margin-bottom:12px;">
            <div class="edit-img" id="editImgPreview"></div>
        </div>
        <div class="edit-img-btn-row" style="margin-bottom:18px;">
            <button type="button" class="edit-img-btn" id="editImgBtn">Ubah Gambar</button>
        </div>
        @if(session('success'))
            <div style="background:#d4edda;color:#155724;padding:12px 18px;border-radius:8px;margin-bottom:18px;text-align:center;">{{ session('success') }}</div>
        @endif
        <form id="editForm" method="POST" action="" enctype="multipart/form-data" style="width:100%;margin-top:8px;">
            @csrf
            @method('POST')
            <input type="file" id="editImgFile" name="gambar" style="display:none;">
            <div class="input-group">
                <label>Judul</label>
                <input type="text" class="input-field" name="judul" id="editJudul">
            </div>
            <div class="input-group">
                <label>Deskripsi</label>
                <input type="text" class="input-field" name="deskripsi" id="editDeskripsi">
            </div>
            <div class="input-group">
                <label>Status</label>
                <div class="select-wrapper">
                    <select class="input-field" name="status" id="editStatus">
                        <option value="" selected disabled>Pilih status...</option>
                        <option value="Tersedia">Tersedia</option>
                        <option value="Tidak tersedia">Tidak tersedia</option>
                    </select>
                    <span class="select-arrow">&#9660;</span>
                </div>
            </div>
            <div class="input-group">
                <label>Waktu Booking</label>
                <div class="select-wrapper">
                    <select class="input-field" name="waktu_booking" id="editWaktuBooking" style="width:100%;">
                        <option value="" selected disabled>Pilih waktu booking...</option>
                        @foreach($jamList as $jam)
                            <option value="{{ $jam }}" class="option-jam">{{ $jam }}</option>
                        @endforeach
                    </select>
                    <span class="select-arrow">&#9660;</span>
                </div>
            </div>
            <div class="edit-btn-row" style="justify-content:center;margin-top:24px;">
                <button type="submit" class="edit-update-btn">Perbarui</button>
            </div>
        </form>
    </div>
</div>
<style>
    .option-jam.tidak-tersedia {
        background: #e74c3c !important;
        color: #fff !important;
    }
</style>
<script>
// Custom dropdown
const editDropdown = document.getElementById('editDropdown');
const lapanganDropdownMenu = document.getElementById('lapanganDropdownMenu');
const editDropdownArrow = document.getElementById('editDropdownArrow');
let selectedLapangan = null;
editDropdown.addEventListener('click', function() {
    if (lapanganDropdownMenu.style.display === 'none') {
        lapanganDropdownMenu.style.display = 'block';
        editDropdownArrow.innerHTML = '&#9650;';
    } else {
        lapanganDropdownMenu.style.display = 'none';
        editDropdownArrow.innerHTML = '&#9660;';
    }
});
document.querySelectorAll('.lapangan-dropdown-item').forEach(function(item) {
    item.addEventListener('click', function() {
        selectedLapangan = item;
        editDropdown.innerHTML = item.innerText + ' <span class="edit-dropdown-arrow" id="editDropdownArrow">&#9660;</span>';
        lapanganDropdownMenu.style.display = 'none';
        // Isi form
        document.getElementById('editJudul').value = item.getAttribute('data-nama') || '';
        document.getElementById('editDeskripsi').value = item.getAttribute('data-deskripsi') || '';
        // Gambar
        const gambar = item.getAttribute('data-gambar');
        let imgHtml = '<div style="width:100%;height:100%;background:#ccc;"></div>';
        if (gambar) {
            imgHtml = `<img src="data:image/jpeg;base64,${gambar}" style="width:100%;height:100%;object-fit:cover;">`;
        }
        document.getElementById('editImgPreview').innerHTML = imgHtml;
        // Set form action
        document.getElementById('editForm').action = '/lapangan/update/' + item.getAttribute('data-id');
        // Highlight option merah jika tidak tersedia
        let statusJam = {};
        try { statusJam = JSON.parse(item.getAttribute('data-status_jam') || '{}'); } catch(e) {}
        let jamTidakTersedia = [];
        if (Object.keys(statusJam).length > 0) {
            for (const jam in statusJam) {
                if (statusJam[jam] === 'Tidak tersedia') jamTidakTersedia.push(jam);
            }
        } else if (item.getAttribute('data-waktu_booking')) {
            jamTidakTersedia = item.getAttribute('data-waktu_booking').split(',').map(j => j.trim());
        }
        document.querySelectorAll('#editWaktuBooking option.option-jam').forEach(function(opt) {
            if (jamTidakTersedia.includes(opt.value)) {
                opt.classList.add('tidak-tersedia');
            } else {
                opt.classList.remove('tidak-tersedia');
            }
        });
    });
});
// Ubah gambar
const editImgBtn = document.getElementById('editImgBtn');
const editImgFile = document.getElementById('editImgFile');
editImgBtn.addEventListener('click', function() {
    editImgFile.click();
});
editImgFile.addEventListener('change', function(e) {
    if (editImgFile.files.length > 0) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('editImgPreview').innerHTML = `<img src="${e.target.result}" style='width:100%;height:100%;object-fit:cover;'>`;
        };
        reader.readAsDataURL(editImgFile.files[0]);
    }
});
// Tutup dropdown jika klik di luar
window.addEventListener('click', function(e) {
    if (!editDropdown.contains(e.target) && !lapanganDropdownMenu.contains(e.target)) {
        lapanganDropdownMenu.style.display = 'none';
        editDropdownArrow.innerHTML = '&#9660;';
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil diperbarui',
            text: '',
            confirmButtonText: 'OK'
        });
    });
</script>
@endif
@endsection 