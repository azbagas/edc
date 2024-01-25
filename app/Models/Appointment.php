<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Appointment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $with = ['doctor', 'assistant', 'admin', 'patient', 'treatments', 'diagnoses', 'medicines', 'status', 'payment'];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    public function assistant(): BelongsTo
    {
        return $this->belongsTo(Assistant::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function medicines(): BelongsToMany
    {
        return $this->belongsToMany(Medicine::class)->withPivot('price', 'quantity')->withTimestamps();
    }

    public function treatments(): BelongsToMany
    {
        return $this->belongsToMany(Treatment::class)->withPivot('price', 'note')->withTimestamps();
    }

    public function diagnoses(): BelongsToMany
    {
        return $this->belongsToMany(Diagnosis::class)->withPivot('note')->withTimestamps();
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }
}
