<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PatientPromise extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $with = ['patient'];

    public const STATUS = ['Pending', 'Batal', 'Selesai'];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
