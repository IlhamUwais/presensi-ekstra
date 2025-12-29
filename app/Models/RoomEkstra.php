<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoomEkstra extends Model
{
    protected $table = 'room_ekstras'; 
    protected $guarded = [];

    // Satu Ruangan dipakai di BANYAK Jadwal
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }
}
