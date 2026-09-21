<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('borrowings', function (Blueprint $table) {
            $table->id();
            $table->string('borrow_code')->unique(); // e.g. BRW-20260920-001
            $table->foreignId('borrower_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('custodian_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('purpose');
            $table->dateTime('request_date');
            $table->dateTime('expected_return_date');
            $table->dateTime('released_at')->nullable();
            $table->dateTime('returned_at')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected, released, returned, overdue, cancelled
            $table->text('notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrowings');
    }
};
