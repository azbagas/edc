<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Owner extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $with = ['user'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
