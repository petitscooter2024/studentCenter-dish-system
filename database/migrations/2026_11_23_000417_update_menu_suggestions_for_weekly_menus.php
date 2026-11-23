
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('menu_suggestions', 'weekly_menu_id')) {
            Schema::table('menu_suggestions', function (Blueprint $table) {
                $table->foreignId('weekly_menu_id')
                    ->nullable()
                    ->constrained('weekly_menus')
                    ->nullOnDelete();
            });
        }

        if (!Schema::hasColumn('menu_suggestions', 'weekly_menu_item_id')) {
            Schema::table('menu_suggestions', function (Blueprint $table) {
                $table->foreignId('weekly_menu_item_id')
                    ->nullable()
                    ->constrained('weekly_menu_items')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        // Keep existing menu suggestion columns and records safe.
    }
};