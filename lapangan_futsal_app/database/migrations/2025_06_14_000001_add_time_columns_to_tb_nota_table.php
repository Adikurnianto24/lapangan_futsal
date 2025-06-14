<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_nota', function (Blueprint $table) {
            $table->dateTime('mulai')->nullable(false)->after('waktu_pemesanan');
            $table->dateTime('selesai')->nullable(false)->after('mulai');
            $table->string('status')->default('booked')->after('selesai');
        });
    }

    public function down(): void
    {
        Schema::table('tb_nota', function (Blueprint $table) {
            $table->dropColumn(['mulai', 'selesai', 'status']);
        });
    }
}; 