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
        Schema::create('patient_promises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained();
            $table->dateTime('date_time');
            $table->text('note')->nullable();
            $table->enum('status', ['Pending', 'Batal', 'Selesai'])->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_promises');
    }
};
