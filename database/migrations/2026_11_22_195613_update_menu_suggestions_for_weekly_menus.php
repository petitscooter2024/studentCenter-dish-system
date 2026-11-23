<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menu_suggestions', function (Blueprint $table) {
            $table->foreignId('weekly_menu_id')
                ->nullable()
                ->after('daily_menu_id')
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('weekly_menu_item_id')
                ->nullable()
                ->after('weekly_menu_id')
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('daily_menu_id')
                ->nullable()
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('menu_suggestions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('weekly_menu_item_id');
            $table->dropConstrainedForeignId('weekly_menu_id');

            $table->foreignId('daily_menu_id')
                ->nullable(false)
                ->change();
        });
    }
};