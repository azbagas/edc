<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patient extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    // Accessor and Mutators
    protected function dateOfBirth(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::createFromFormat('Y-m-d', $value)->format('d-m-Y'),
            set: fn (string $value) => Carbon::createFromFormat('d-m-Y', $value)->format('Y-m-d')
        );
    }

    protected function age(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => Carbon::parse($attributes['date_of_birth'])->age 
        );
    }

    // protected function gender(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn (string $value) =>  $value == 'Laki-laki' ? 'L' : 'P',
    //     );
    // }
}
