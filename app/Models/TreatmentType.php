<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TreatmentType extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function treatments(): HasMany
    {
        return $this->hasMany(Treatment::class);
    }
}
