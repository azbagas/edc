<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Medicine extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function appointments(): BelongsToMany
    {
        return $this->belongsToMany(Appointment::class)->withPivot('price', 'quantity')->withTimestamps();
    }

    public function medicine_type(): BelongsTo
    {
        return $this->belongsTo(MedicineType::class);
    }
}
