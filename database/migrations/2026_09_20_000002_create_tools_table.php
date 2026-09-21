<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tools', function (Blueprint $table) {
            $table->id();
            $table->string('asset_code')->unique(); // e.g. BIND-HT-001
            $table->string('name');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('brand_model')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('location_storage')->default('BIND-Tech Main Cabinet');
            $table->integer('total_qty')->default(1);
            $table->integer('available_qty')->default(1);
            $table->string('status')->default('available'); // available, in_use, under_maintenance, damaged, lost, retired
            $table->string('condition')->default('good'); // new, good, fair, needs_repair, damaged
            $table->string('image_path')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tools');
    }
};
