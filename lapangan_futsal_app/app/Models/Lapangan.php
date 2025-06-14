<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lapangan extends Model
{
    protected $table = 'tb_lapangan';
    public $timestamps = false;
    protected $fillable = [
        'nama_lapangan',
        'deskripsi_lapangan',
        'status',
        'gambar',
        'waktu_booking',
        'status_jam',
    ];
    protected $primaryKey = 'id_lapangan';
} 