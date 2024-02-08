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
        Schema::table('patient_conditions', function (Blueprint $table) {
            $table->after('is_pregnant', function (Blueprint $table) {
                $table->boolean('is_diabetes')->default(0);
                $table->boolean('is_hypertension')->default(0);
                $table->text('note')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patient_conditions', function (Blueprint $table) {
            $table->dropColumn('is_diabetes');
            $table->dropColumn('is_hypertension');
            $table->dropColumn('note');
        });
    }
};
