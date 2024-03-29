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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 16, 2);
            $table->decimal('operational_cost', 16, 2)->default(0);
            $table->decimal('lab_cost', 16, 2)->default(0);
            $table->decimal('doctor_percentage');
            $table->text('note')->nullable();
            $table->enum('status', ['Lunas', 'Belum lunas']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
