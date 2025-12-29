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
        Schema::create('schedules', function (Blueprint $table) {
           $table->id();
             $table->foreignId('ekstrakulikuler_id')->constrained('ekstrakulikulers')->onDelete('cascade');
            // Jika ruangan dihapus, jadwal jadi null (jangan dihapus jadwalnya)
          $table->foreignId('room_ekstra_id')->nullable()->constrained('room_ekstras')->onDelete('set null');
            
            $table->enum('day_of_week', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']);
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
