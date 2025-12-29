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
        Schema::create('room_ekstras', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            
            // 1. Tambahkan kolom location
            $table->string('location')->nullable(); 
            
            $table->decimal('latitude', 10, 8); 
            $table->decimal('longitude', 11, 8);
            
            // 2. Ubah nama dari 'radius_meter' jadi 'radius' (biar sama dengan form)
            $table->integer('radius')->default(20); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_ekstras');
    }
};
