
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_suggestions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('daily_menu_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('daily_menu_item_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('food_item_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('action');
            $table->text('reason')->nullable();

            $table->string('status')->default('pending');

            $table->foreignId('submitted_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_suggestions');
    }
};
