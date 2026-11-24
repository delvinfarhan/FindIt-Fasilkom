<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('civitas_found_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_code')->unique()->index(); // e.g. TMN-2026-0012
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Civitas penemu
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('item_name'); // e.g. Payung lipat biru
            $table->date('found_date');
            $table->string('found_location'); // Area parkir FASILKOM
            $table->text('public_description');
            $table->text('private_features')->nullable();
            $table->string('temp_storage')->default('Masih disimpan penemu, siap diserahkan ke pos security');
            $table->string('contact_phone');
            $table->string('image_path')->nullable();
            $table->enum('status', ['menunggu_penyerahan', 'diterima_security', 'dibatalkan'])->default('menunggu_penyerahan')->index();
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('received_at')->nullable();
            $table->foreignId('found_item_id')->nullable()->constrained('found_items')->nullOnDelete();
            $table->string('storage_location')->nullable(); // e.g. Lemari B • Rak 01
            $table->text('officer_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('civitas_found_reports');
    }
};
