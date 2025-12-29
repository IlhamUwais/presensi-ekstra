<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $guarded = [];
    protected $table = 'attendances';
    public function user()
{
    return $this->belongsTo(User::class);
}
public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }
}
