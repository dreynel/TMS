<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->index(['role', 'is_approved'], 'idx_users_role_approved');
        });

        Schema::table('tools', function (Blueprint $table) {
            $table->index('status', 'idx_tools_status');
            $table->index('condition', 'idx_tools_condition');
        });

        Schema::table('borrowings', function (Blueprint $table) {
            $table->index('status', 'idx_borrowings_status');
            $table->index('request_date', 'idx_borrowings_request_date');
            $table->index('expected_return_date', 'idx_borrowings_expected_return_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_role_approved');
        });

        Schema::table('tools', function (Blueprint $table) {
            $table->dropIndex('idx_tools_status');
            $table->dropIndex('idx_tools_condition');
        });

        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropIndex('idx_borrowings_status');
            $table->dropIndex('idx_borrowings_request_date');
            $table->dropIndex('idx_borrowings_expected_return_date');
        });
    }
};
