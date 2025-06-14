<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Nota;
use App\Models\Lapangan;

class UpdateBookingStatus extends Command
{
    protected $signature = 'booking:update';
    protected $description = 'Memeriksa nota yang telah selesai dan membebaskan slot lapangan.';

    public function handle(): int
    {
        $now = now();
        // Ambil semua booking yang sudah lewat tetapi masih berstatus booked
        $notas = Nota::where('status', 'booked')
            ->where('selesai', '<=', $now)
            ->get();

        foreach ($notas as $nota) {
            $lapangan = $nota->lapangan;
            if (!$lapangan) {
                $nota->status = 'finished';
                $nota->save();
                continue;
            }

            $jamText = $nota->waktu_pemesanan;

            // Bersihkan status_jam
            $statusJam = $lapangan->status_jam ? json_decode($lapangan->status_jam, true) : [];
            if (isset($statusJam[$jamText])) {
                unset($statusJam[$jamText]);
                $lapangan->status_jam = json_encode($statusJam);
            }

            // Bersihkan waktu_booking
            $daftarJam = $lapangan->waktu_booking ? array_map('trim', explode(',', $lapangan->waktu_booking)) : [];
            $daftarJam = array_values(array_diff($daftarJam, [$jamText]));
            $lapangan->waktu_booking = implode(', ', $daftarJam);
            $lapangan->save();

            // Tandai nota selesai
            $nota->status = 'finished';
            $nota->save();
        }

        $this->info('Update booking selesai. Diperbarui: ' . $notas->count());
        return Command::SUCCESS;
    }
} 