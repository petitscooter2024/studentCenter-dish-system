
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('weekly_menu_items', function (Blueprint $table) {
            $table->dropForeign(['food_item_id']);
        });

        Schema::table('daily_menu_items', function (Blueprint $table) {
            $table->dropForeign(['food_item_id']);
        });

        Schema::table('weekly_menu_items', function (Blueprint $table) {
            $table->foreign('food_item_id')
                ->references('id')
                ->on('food_items')
                ->restrictOnDelete();
        });

        Schema::table('daily_menu_items', function (Blueprint $table) {
            $table->foreign('food_item_id')
                ->references('id')
                ->on('food_items')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('weekly_menu_items', function (Blueprint $table) {
            $table->dropForeign(['food_item_id']);
        });

        Schema::table('daily_menu_items', function (Blueprint $table) {
            $table->dropForeign(['food_item_id']);
        });

        Schema::table('weekly_menu_items', function (Blueprint $table) {
            $table->foreign('food_item_id')
                ->references('id')
                ->on('food_items')
                ->cascadeOnDelete();
        });

        Schema::table('daily_menu_items', function (Blueprint $table) {
            $table->foreign('food_item_id')
                ->references('id')
                ->on('food_items')
                ->cascadeOnDelete();
        });
    }
};
