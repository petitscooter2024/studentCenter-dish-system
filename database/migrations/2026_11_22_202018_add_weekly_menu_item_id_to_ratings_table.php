
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            $table->foreignId('weekly_menu_item_id')
                ->nullable()
                ->after('daily_menu_item_id')
                ->constrained('weekly_menu_items')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('weekly_menu_item_id');
        });
    }
};
