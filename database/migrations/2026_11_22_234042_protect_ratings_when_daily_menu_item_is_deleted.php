
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            $table->dropForeign(['daily_menu_item_id']);
        });

        Schema::table('ratings', function (Blueprint $table) {
            $table->foreign('daily_menu_item_id')
                ->references('id')
                ->on('daily_menu_items')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            $table->dropForeign(['daily_menu_item_id']);
        });

        Schema::table('ratings', function (Blueprint $table) {
            $table->foreign('daily_menu_item_id')
                ->references('id')
                ->on('daily_menu_items')
                ->cascadeOnDelete();
        });
    }
};
