<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    protected $table = 'tb_nota';
    public $timestamps = false;
    protected $fillable = [
        'id_lapangan',
        'nama_pemesan',
        'kontak_pemesan',
        'waktu_pemesanan',
        'mulai',
        'selesai',
        'status',
    ];
    protected $primaryKey = 'id_nota';

    public function lapangan() {
        return $this->belongsTo(Lapangan::class, 'id_lapangan', 'id_lapangan');
    }
} 