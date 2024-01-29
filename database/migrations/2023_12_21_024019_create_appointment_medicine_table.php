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
        Schema::create('appointment_medicine', function (Blueprint $table) {
            $table->foreignId('appointment_id')->constrained()->onDelete('cascade');
            $table->foreignId('medicine_id')->constrained();
            $table->integer('quantity');
            $table->decimal('price', 16, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_medicine');
    }
};
