<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Diagnosis extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $with = ['disease'];

    public function appointments(): BelongsToMany
    {
        return $this->belongsToMany(Appointment::class)->withPivot('note')->withTimestamps();
    }

    public function disease(): BelongsTo
    {
        return $this->belongsTo(Disease::class);
    }
}
