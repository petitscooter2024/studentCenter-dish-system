<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // category_id is already created in the original migration.
    }

    public function down(): void
    {
        // Nothing to reverse.
    }
};