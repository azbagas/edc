<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assistant extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
}
