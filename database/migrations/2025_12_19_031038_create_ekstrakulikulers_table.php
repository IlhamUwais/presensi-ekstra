<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ekstrakulikulers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: Basket, Voli
            
            // 1. RELASI KE LOKASI (ROOM)
            $table->foreignId('room_ekstra_id')
                ->constrained('room_ekstras')
                ->cascadeOnDelete();

            // 2. RELASI KE PEMBINA (USERS)
            // Hanya user dengan role 'pembina' yang nanti kita ambil di Filament
            $table->foreignId('pembina_id')
                ->nullable() // Boleh null jika belum ada pembinanya
                ->constrained('users')
                ->nullOnDelete(); // Jika user dihapus, data ekstra tetap ada (cuma pembinanya jadi kosong)

            // 3. JADWAL
            $table->string('hari')->nullable();
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ekstrakulikulers');
    }
};