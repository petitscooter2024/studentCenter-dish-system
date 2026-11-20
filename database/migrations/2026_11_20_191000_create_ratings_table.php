<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('daily_menu_item_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->unsignedTinyInteger('rating');
            $table->text('comment')->nullable();
            $table->string('anonymous_token_hash', 64);

            $table->timestamps();

            $table->unique([
                'daily_menu_item_id',
                'anonymous_token_hash'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};