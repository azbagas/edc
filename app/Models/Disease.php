<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Disease extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function diagnoses(): HasMany
    {
        return $this->hasMany(Diagnosis::class);
    }
}
