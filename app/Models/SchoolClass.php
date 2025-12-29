<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolClass extends Model
{
    protected $guarded = [];
    public function students(): HasMany
    {
        return $this->hasMany(User::class, 'school_class_id');
    }
}
