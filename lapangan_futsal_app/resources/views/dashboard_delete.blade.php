@extends('layouts.dashboard')
@section('content')
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
                    $jamTidakTersedia = $l->waktu_booking ? array_map('trim', explode(',', $l->waktu_booking)) : [];
                @endphp
                <div class="lapangan-row-jam-grid">
                    @foreach($jamList as $jam)
                        <span class="{{ in_array($jam, $jamTidakTersedia) ? 'jam-tidak-tersedia' : 'jam-tersedia' }}">{{ $jam }}</span>
                    @endforeach
                </div>
            </div>
            <form method="POST" action="{{ route('lapangan.delete', $l->id_lapangan) }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete-btn">Delete</button>
            </form>
        </div>
        @endforeach
    </div>
@endif
@endsection

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil dihapus',
            text: '',
            confirmButtonText: 'OK'
        });
    });
</script>
@endif 