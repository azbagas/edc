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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained();
            $table->foreignId('admin_id')->constrained();
            $table->foreignId('assistant_id')->constrained();
            $table->foreignId('patient_id')->constrained();
            $table->text('complaint');
            $table->dateTime('date_time');
            $table->dateTime('next_appointment_date_time')->nullable();
            $table->foreignId('status_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
