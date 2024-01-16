<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
