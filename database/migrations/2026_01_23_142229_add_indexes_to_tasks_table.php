<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Add missing foreign key indexes (important!)
            $table->index('created_by');
            $table->index('assigned_to');

            // Index for status queries (very common)
            $table->index('status');

            // Index for priority queries (now VARCHAR, needs index)
            $table->index('priority');

            // Index for due date queries
            $table->index('due_date');

            // Composite index for common task filtering patterns
            $table->index(['status', 'priority']);
            $table->index(['assigned_to', 'status']);
            $table->index(['created_by', 'created_at']);

            // Covering index for task listings
            $table->index(['status', 'priority', 'due_date', 'assigned_to']);

            // Index for date-based queries
            $table->index('created_at');

            // Full-text search for title and description (if using MySQL)
            // $table->fullText(['title', 'description']);
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropIndex(['created_by']);
            $table->dropIndex(['assigned_to']);
            $table->dropIndex(['status']);
            $table->dropIndex(['priority']);
            $table->dropIndex(['due_date']);
            $table->dropIndex(['status', 'priority']);
            $table->dropIndex(['assigned_to', 'status']);
            $table->dropIndex(['created_by', 'created_at']);
            $table->dropIndex(['status', 'priority', 'due_date', 'assigned_to']);
            $table->dropIndex(['created_at']);
            // $table->dropFullText(['title', 'description']);
        });
    }
};
