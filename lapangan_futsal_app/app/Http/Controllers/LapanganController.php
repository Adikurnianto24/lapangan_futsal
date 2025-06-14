<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Lapangan;
use App\Models\Nota;
use Carbon\Carbon;

class LapanganController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'deskripsi' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $gambarBlob = null;
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $gambarBlob = file_get_contents($file); // simpan binary ke BLOB
        }

        Lapangan::create([
            'nama_lapangan' => $request->judul,
            'deskripsi_lapangan' => $request->deskripsi,
            'gambar' => $gambarBlob,
        ]);

        return redirect()->back()->with('success', 'Lapangan berhasil ditambahkan!');
    }

    public function index()
    {
        $lapangan = Lapangan::all();
        return view('dashboard', compact('lapangan'));
    }

    public function input() {
        return view('dashboard_input');
    }

    public function edit() {
        $lapangan = Lapangan::all();
        return view('dashboard_edit', compact('lapangan'));
    }

    public function delete() {
        $lapangan = Lapangan::all();
        return view('dashboard_delete', compact('lapangan'));
    }

    public function nota() {
        $lapangan = Lapangan::all();
        $nota = Nota::with('lapangan')->orderBy('id_nota', 'desc')->get();
        return view('dashboard_nota', compact('lapangan', 'nota'));
    }

    public function destroy($id) {
        $lapangan = Lapangan::findOrFail($id);
        $lapangan->delete();
        return redirect()->route('dashboard.delete')->with('success', 'Lapangan berhasil dihapus!');
    }

    public function update(Request $request, $id) {
        $lapangan = Lapangan::findOrFail($id);

        // Ambil status dan jam yang dipilih
        $status = $request->input('status');
        $jam = $request->input('waktu_booking');

        // Ambil status_jam lama
        $statusJam = [];
        if ($lapangan->status_jam) {
            $statusJam = json_decode($lapangan->status_jam, true) ?? [];
        }

        // Update status jam yang dipilih hanya jika diisi
        if ($jam && $status) {
            $statusJam[$jam] = $status;
            $lapangan->status_jam = json_encode($statusJam);
            // Sinkronkan juga ke waktu_booking
            $daftarJam = [];
            if ($lapangan->waktu_booking) {
                $daftarJam = array_map('trim', explode(',', $lapangan->waktu_booking));
            }
            if ($status === 'Tidak tersedia' && !in_array($jam, $daftarJam)) {
                $daftarJam[] = $jam;
            } else if ($status === 'Tersedia' && in_array($jam, $daftarJam)) {
                $daftarJam = array_diff($daftarJam, [$jam]);
            }
            $lapangan->waktu_booking = implode(', ', array_values($daftarJam));
        }
        // Jika tidak diisi, biarkan status_jam lama

        // Field lain tetap seperti biasa
        $lapangan->nama_lapangan = $request->judul;
        $lapangan->deskripsi_lapangan = $request->deskripsi;
        // Hanya perbarui status global jika pengisian tidak spesifik jam
        if (!$jam && $status) {
            $lapangan->status = $status;
        }
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $lapangan->gambar = file_get_contents($file);
        }
        $lapangan->save();

        return redirect()->route('dashboard.edit')->with('success', 'Lapangan berhasil diperbarui!');
    }

    public function showDetail($id)
    {
        $lapangan = Lapangan::findOrFail($id);
        $semuaLapangan = Lapangan::orderBy('id_lapangan')->get();
        $nomor_lapangan = 1;
        foreach ($semuaLapangan as $idx => $l) {
            if ($l->id_lapangan == $lapangan->id_lapangan) {
                $nomor_lapangan = $idx + 1;
                break;
            }
        }
        return view('lapangan_detail', compact('lapangan', 'nomor_lapangan'));
    }

    public function storeNota(Request $request)
    {
        $request->validate([
            'lapangan' => 'required',
            'nama_pemesan' => 'required',
            'no_telepon' => 'required',
            'waktu_booking' => 'required',
        ]);

        // Cek ketersediaan jam terlebih dulu (hindari double-booking)
        $lapanganCek = Lapangan::findOrFail($request->lapangan);
        $statusJamExist = $lapanganCek->status_jam ? json_decode($lapanganCek->status_jam, true) : [];
        if (isset($statusJamExist[$request->waktu_booking]) && $statusJamExist[$request->waktu_booking] === 'Tidak tersedia') {
            return back()->withErrors(['waktu_booking' => 'Jam tersebut sudah terisi'])->withInput();
        }

        // Hitung waktu mulai & selesai (berbasis tanggal hari ini)
        $today = now();
        [$startStr, $endStr] = array_map('trim', explode('-', str_replace(' ', '', $request->waktu_booking)));
        // Convert format 10.00 menjadi 10:00
        $startStr = str_replace('.', ':', $startStr);
        $endStr   = str_replace('.', ':', $endStr);
        $mulai   = $today->copy()->setTimeFromTimeString($startStr);
        // Jika endStr < startStr (tidak mungkin utk format 1 jam, tapi jaga-jaga)
        $selesai = $today->copy()->setTimeFromTimeString($endStr);
        if ($selesai->lessThanOrEqualTo($mulai)) {
            $selesai = $mulai->copy()->addHour();
        }

        // Simpan ke tb_nota
        Nota::create([
            'id_lapangan'      => $request->lapangan,
            'nama_pemesan'     => $request->nama_pemesan,
            'kontak_pemesan'   => $request->no_telepon,
            'waktu_pemesanan'  => $request->waktu_booking,
            'mulai'            => $mulai,
            'selesai'          => $selesai,
            'status'           => 'booked',
        ]);

        // Update waktu_booking lapangan (tambah jam ke daftar tidak tersedia)
        $lapangan = Lapangan::findOrFail($request->lapangan);
        $daftarJam = [];
        if ($lapangan->waktu_booking) {
            $daftarJam = array_map('trim', explode(',', $lapangan->waktu_booking));
        }
        if (!in_array($request->waktu_booking, $daftarJam)) {
            $daftarJam[] = $request->waktu_booking;
        }
        $lapangan->waktu_booking = implode(', ', array_values($daftarJam));
        // Update status_jam juga
        $statusJam = [];
        if ($lapangan->status_jam) {
            $statusJam = json_decode($lapangan->status_jam, true) ?? [];
        }
        $statusJam[$request->waktu_booking] = 'Tidak tersedia';
        $lapangan->status_jam = json_encode($statusJam);
        $lapangan->save();

        return redirect()->route('dashboard.nota')->with('success', 'Nota berhasil ditambahkan dan waktu booking diupdate!');
    }

    public function updateNota(Request $request, $id)
    {
        $nota = Nota::findOrFail($id);
        $lapangan = Lapangan::findOrFail($nota->id_lapangan);
        $jamLama = $nota->waktu_pemesanan;
        $jamBaru = $request->waktu_booking;
        // Cek ketersediaan jam baru terlebih dulu
        $statusJamExist = $lapangan->status_jam ? json_decode($lapangan->status_jam, true) : [];
        if (isset($statusJamExist[$jamBaru]) && $statusJamExist[$jamBaru] === 'Tidak tersedia' && $jamBaru !== $jamLama) {
            return back()->withErrors(['waktu_booking' => 'Jam tersebut sudah terisi'])->withInput();
        }

        // Hitung ulang waktu mulai & selesai dgn tanggal hari ini
        $today = now();
        [$startStr, $endStr] = array_map('trim', explode('-', str_replace(' ', '', $jamBaru)));
        $startStr = str_replace('.', ':', $startStr);
        $endStr   = str_replace('.', ':', $endStr);
        $mulai   = $today->copy()->setTimeFromTimeString($startStr);
        $selesai = $today->copy()->setTimeFromTimeString($endStr);
        if ($selesai->lessThanOrEqualTo($mulai)) {
            $selesai = $mulai->copy()->addHour();
        }

        // Update nota
        $nota->nama_pemesan     = $request->nama_pemesan;
        $nota->kontak_pemesan   = $request->no_telepon;
        $nota->waktu_pemesanan  = $jamBaru;
        $nota->mulai            = $mulai;
        $nota->selesai          = $selesai;
        $nota->save();
        // Update status_jam lapangan
        $statusJam = $lapangan->status_jam ? json_decode($lapangan->status_jam, true) : [];
        if ($jamLama && isset($statusJam[$jamLama])) {
            unset($statusJam[$jamLama]);
        }
        $statusJam[$jamBaru] = 'Tidak tersedia';
        $lapangan->status_jam = json_encode($statusJam);
        // Update waktu_booking lapangan
        $daftarJam = [];
        if ($lapangan->waktu_booking) {
            $daftarJam = array_map('trim', explode(',', $lapangan->waktu_booking));
        }
        // Hapus jam lama, tambah jam baru
        $daftarJam = array_values(array_diff($daftarJam, [$jamLama]));
        if (!in_array($jamBaru, $daftarJam)) {
            $daftarJam[] = $jamBaru;
        }
        $lapangan->waktu_booking = implode(', ', array_values($daftarJam));
        $lapangan->save();
        return redirect()->route('dashboard.nota')->with('success', 'Nota berhasil diubah!');
    }

    public function destroyNota($id)
    {
        $nota = Nota::findOrFail($id);

        // Pastikan referensi lapangan ada
        if ($nota) {
            $lapangan = Lapangan::find($nota->id_lapangan);
            if ($lapangan) {
                $jam = $nota->waktu_pemesanan;

                // Bersihkan status_jam
                $statusJam = $lapangan->status_jam ? json_decode($lapangan->status_jam, true) : [];
                if (isset($statusJam[$jam])) {
                    unset($statusJam[$jam]);
                    $lapangan->status_jam = json_encode($statusJam);
                }

                // Bersihkan waktu_booking
                $daftarJam = $lapangan->waktu_booking ? array_map('trim', explode(',', $lapangan->waktu_booking)) : [];
                $daftarJam = array_values(array_diff($daftarJam, [$jam]));
                $lapangan->waktu_booking = implode(', ', $daftarJam);

                $lapangan->save();
            }
        }

        $nota->delete();
        return redirect()->route('dashboard.nota')->with('success', 'Nota berhasil dihapus!');
    }
} 