<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('found_items', function (Blueprint $table) {
            $table->id();
            $table->string('item_code')->unique()->index(); // e.g. FND-2026-0142
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('name'); // Nama umum (publik)
            $table->date('found_date');
            $table->time('found_time')->nullable();
            $table->string('found_location'); // Lokasi umum publik
            $table->text('public_description'); // Deskripsi umum tanpa bocoran ciri sensitif
            $table->text('private_description'); // Ciri rahasia / privat untuk verifikasi
            $table->string('storage_location')->default('Lemari A • Rak 01'); // Pos fisik security
            $table->string('image_path')->nullable();
            $table->enum('status', ['tersedia', 'diverifikasi', 'dikembalikan', 'diarsipkan'])->default('tersedia')->index();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete(); // Petugas pencatat
            $table->foreignId('handed_over_to_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('handed_over_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('found_items');
    }
};
