
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('daily_menus', function (Blueprint $table) {
            $table->string('publication_status')
                ->default('draft')
                ->after('title');

            $table->foreignId('weekly_menu_id')
                ->nullable()
                ->after('publication_status')
                ->constrained()
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('daily_menus', function (Blueprint $table) {
            $table->dropConstrainedForeignId('weekly_menu_id');
            $table->dropColumn('publication_status');
        });
    }
};
