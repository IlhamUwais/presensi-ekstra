<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('attendances', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained(); // Siswa
        $table->foreignId('schedule_id')->constrained(); // Jadwal mana
        $table->date('date'); // Tanggal absen
        
        // Status sesuai request (termasuk setengah)
        $table->enum('status', ['hadir', 'izin', 'sakit', 'setengah', 'alpha']);
        
        $table->time('clock_in')->nullable();
        $table->time('clock_out')->nullable();
        
        // Data Pendukung (Bukti)
        $table->text('reason')->nullable(); // Alasan izin/sakit
        $table->string('photo_in')->nullable(); // Selfie Masuk / Surat Dokter
        $table->string('photo_out')->nullable(); // Selfie Pulang
        
        // Koordinat Bukti (Audit Trail)
        $table->decimal('lat_in', 10, 8)->nullable();
        $table->decimal('long_in', 11, 8)->nullable();
        $table->decimal('lat_out', 10, 8)->nullable();
        $table->decimal('long_out', 11, 8)->nullable();
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
