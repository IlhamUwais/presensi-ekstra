<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ekstrakulikuler extends Model
{
    protected $table = 'ekstrakulikulers';
    protected $guarded = [];

    // 1. Satu Ekstra punya BANYAK Jadwal
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'ekstrakulikuler_id');
    }
    public function members(): HasMany
    {           
        return $this->hasMany(MemberEkstra::class);
    }
    // Tambahkan ini di Model Extracurricular
    public function pembina()
    {
        return $this->belongsTo(User::class, 'pembina_id');
    }
    public function roomEkstra(): BelongsTo
    {
        return $this->belongsTo(RoomEkstra::class);
    }
}
