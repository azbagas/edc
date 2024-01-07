<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function payment_type(): BelongsTo
    {
        return $this->belongsTo(PaymentType::class);
    }
}
