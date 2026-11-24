<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lost_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_code')->unique()->index(); // e.g. LPR-2026-0087
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('item_name');
            $table->date('lost_date');
            $table->time('lost_time')->nullable();
            $table->string('lost_location');
            $table->text('public_description');
            $table->text('private_features'); // Ciri khusus rahasia
            $table->string('contact_phone');
            $table->string('image_path')->nullable();
            $table->enum('status', ['menunggu', 'diproses', 'cocok', 'selesai', 'dibatalkan'])->default('menunggu')->index();
            $table->foreignId('matched_found_item_id')->nullable()->constrained('found_items')->nullOnDelete();
            $table->text('officer_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lost_reports');
    }
};
