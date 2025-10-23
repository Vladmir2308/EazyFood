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
        Schema::create('meal_schedules', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('dish_id')->nullable()->constrained('dishes')->cascadeOnDelete();
            $table->foreignId('type_id')->nullable()->constrained('types')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meal_schedules');
    }
};
