<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Panel;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
   protected $fillable = [
        'name',
        'username', // Pastikan ini ada
        'password',
        'role',     // Pastikan ini ada
        'nis',
        'nip',
        'school_class_id',
        'is_active',
    ];
    // Relasi ke Kelas (Khusus Siswa)

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id');
    }

    public function memberEkstras(): HasMany
    {
        return $this->hasMany(MemberEkstra::class);
    }
    
    // (Opsional) Jika User bisa jadi pembina ekskul (langsung ada di table extracurriculars kolom pembina_id)
    public function guidedExtracurriculars(): HasMany
    {
        return $this->hasMany(Ekstrakulikuler::class, 'pembina_id'); // Sesuaikan nama kolom foreign key
    }

    public function canAccessPanel(Panel $panel): bool
{
    if ($panel->getId() === 'admin') {
        return $this->role ==='admin';
    }

    if ($panel->getId() === 'pembina') {
        return $this->role === 'pembina';
    }

    if ($panel->getId() === 'siswa') {
        return $this->role === 'siswa';
    }

    return false;
}


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
