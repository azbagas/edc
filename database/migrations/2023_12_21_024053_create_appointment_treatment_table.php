<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appointment_treatment', function (Blueprint $table) {
            $table->foreignId('appointment_id')->constrained();
            $table->foreignId('treatment_id')->constrained();
            $table->decimal('price', 16, 2);
            $table->text('note');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_treatment');
    }
};
