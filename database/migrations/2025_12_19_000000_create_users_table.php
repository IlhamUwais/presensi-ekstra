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
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        
        // 1. INI KOLOM LOGIN (WAJIB ISI)
        // Admin isi username bebas, Siswa isi NIS, Pembina isi NIP
        $table->string('username')->unique(); 
        
        $table->string('password');
        
        // 2. ROLE & DATA TAMBAHAN
        $table->enum('role', ['admin', 'pembina', 'siswa'])->default('siswa');
        
        // Data ini BOLEH NULL (Nullable) karena Admin tidak punya NIS/NIP
        $table->string('nis')->nullable(); 
        $table->string('nip')->nullable(); 
        
        // 3. RELASI KE KELAS (Revisi dari class_name string)
        // Khusus Siswa. Kalau Admin/Pembina ini null.
        $table->foreignId('school_class_id')
              ->nullable()
              ->constrained('school_classes')
              ->onDelete('set null');
        
        $table->boolean('is_active')->default(true);
        $table->rememberToken();
        $table->timestamps();
        $table->softDeletes();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
