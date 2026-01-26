<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Index for role-based queries (especially important now that role is VARCHAR)
            $table->index('role');

            // Composite index for common role + date queries
            $table->index(['role', 'created_at']);

            // Index for email verification status
            $table->index('email_verified_at');

            // If you frequently query by name
            $table->index('name');

            // Composite index for user lookup patterns
            $table->index(['email_verified_at', 'created_at']);
        });

        // Add index to password_reset_tokens for cleanup operations
        Schema::table('password_reset_tokens', function (Blueprint $table) {
            $table->index('created_at');
        });

        // Add composite index to sessions table for user session queries
        Schema::table('sessions', function (Blueprint $table) {
            $table->index(['user_id', 'last_activity']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropIndex(['role', 'created_at']);
            $table->dropIndex(['email_verified_at']);
            $table->dropIndex(['name']);
            $table->dropIndex(['email_verified_at', 'created_at']);
        });

        Schema::table('password_reset_tokens', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });

        Schema::table('sessions', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'last_activity']);
        });
    }
};
