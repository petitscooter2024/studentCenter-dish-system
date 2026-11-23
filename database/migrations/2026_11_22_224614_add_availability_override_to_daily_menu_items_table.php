
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('daily_menu_items', function (Blueprint $table) {
            $table->boolean('availability_override')
                ->nullable()
                ->default(null)
                ->after('is_available');
        });
    }

    public function down(): void
    {
        Schema::table('daily_menu_items', function (Blueprint $table) {
            $table->dropColumn('availability_override');
        });
    }
};
