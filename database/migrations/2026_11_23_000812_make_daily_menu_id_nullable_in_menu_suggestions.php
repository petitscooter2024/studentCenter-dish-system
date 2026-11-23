<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menu_suggestions', function (Blueprint $table) {
            $table->dropForeign(['daily_menu_id']);
            $table->unsignedBigInteger('daily_menu_id')->nullable()->change();

            $table->foreign('daily_menu_id')
                ->references('id')
                ->on('daily_menus')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('menu_suggestions', function (Blueprint $table) {
            $table->dropForeign(['daily_menu_id']);
            $table->unsignedBigInteger('daily_menu_id')->nullable(false)->change();

            $table->foreign('daily_menu_id')
                ->references('id')
                ->on('daily_menus')
                ->cascadeOnDelete();
        });
    }
};