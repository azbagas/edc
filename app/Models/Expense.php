<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected function date(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::createFromFormat('Y-m-d', $value)->format('d-m-Y'),
            set: fn (string $value) => Carbon::createFromFormat('d-m-Y', $value)->format('Y-m-d')
        );
    }
}
