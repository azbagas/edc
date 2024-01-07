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
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_type_id')->constrained();
            $table->string('name');
            $table->string('dose')->nullable();
            $table->integer('stock')->default(0);
            $table->string('unit');
            $table->text('description')->nullable();
            $table->decimal('price', 16, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
