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
        Schema::create('payment_payment_type', function (Blueprint $table) {
            $table->foreignId('payment_id')->constrained()->onDelete('cascade');
            $table->foreignId('payment_type_id')->constrained();
            $table->decimal('patient_money', 16, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_payment_type');
    }
};
