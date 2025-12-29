<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberEkstra extends Model
{
    protected $table = 'member_ekstras';

    protected $fillable = [
        'user_id',
        'ekstrakulikuler_id',
        'status',   
        'is_active',
    ];

    // Member ini adalah SATU User (Siswa)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Member ini mendaftar ke SATU Ekstrakurikuler
    public function ekstra(): BelongsTo
    {
        return $this->belongsTo(
            Ekstrakulikuler::class,
            'ekstrakulikuler_id'
        );
    }
}
