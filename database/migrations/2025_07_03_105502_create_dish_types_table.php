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
        Schema::create('dishes_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dish_id')->constrained('dishes')->cascadeOnDelete();
            $table->foreignId('meal_type_id')->constrained('meal_types')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dish_types');
    }
};
