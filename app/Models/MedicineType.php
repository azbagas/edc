<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MedicineType extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function medicines(): HasMany
    {
        return $this->hasMany(Medicine::class);
    }
}
