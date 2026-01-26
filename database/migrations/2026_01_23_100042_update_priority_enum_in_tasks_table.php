<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            DB::statement("ALTER TABLE tasks MODIFY COLUMN priority VARCHAR(255) DEFAULT 'medium'");

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
           DB::statement("ALTER TABLE tasks MODIFY COLUMN priority ENUM('high', 'medium', 'low') DEFAULT 'medium'");
        });
    }
};
