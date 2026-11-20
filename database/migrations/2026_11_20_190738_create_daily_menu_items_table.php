<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_menu_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('daily_menu_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('food_item_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->decimal('price', 10, 2);
            $table->boolean('is_available')->default(true);

            $table->timestamps();

            $table->unique(['daily_menu_id', 'food_item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_menu_items');
    }
};