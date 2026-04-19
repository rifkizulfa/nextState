<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, update existing 'Low' values to 'Easy' to avoid issues with enum change
        DB::statement("UPDATE tasks SET priority = 'Easy' WHERE priority = 'Low'");

        // Then modify the column
        DB::statement("ALTER TABLE tasks MODIFY COLUMN priority ENUM('Easy', 'Medium', 'High') DEFAULT 'Medium'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("UPDATE tasks SET priority = 'Low' WHERE priority = 'Easy'");
        DB::statement("ALTER TABLE tasks MODIFY COLUMN priority ENUM('Low', 'Medium', 'High') DEFAULT 'Medium'");
    }
};
