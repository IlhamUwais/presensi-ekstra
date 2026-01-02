<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    protected $guarded = [];
    protected $table = 'schedules';


    // Jadwal ini milik SATU Ekskul
    public function ekstra(): BelongsTo
    {
       return $this->belongsTo(Ekstrakulikuler::class, 'ekstrakulikuler_id');
    }

    // Jadwal ini menempati SATU Ruangan
    public function roomEkstra(): BelongsTo
    {
        return $this->belongsTo(RoomEkstra::class, 'room_ekstra_id'); 
        // Pastikan di db kolomnya 'room_ekstra_id'
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    
}
