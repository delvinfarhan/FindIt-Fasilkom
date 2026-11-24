<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->string('claim_code')->unique()->index(); // e.g. KLM-2026-0031
            $table->foreignId('found_item_id')->constrained('found_items')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Pemohon klaim (mahasiswa)
            $table->text('chronology'); // Kronologi kehilangan
            $table->text('private_proof_features'); // Ciri khusus bukti yang dicocokkan dengan catatan privat security
            $table->string('contact_phone');
            $table->string('proof_image_path')->nullable(); // Foto bukti privat (misal nota / foto lama)
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending')->index();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete(); // Petugas verifikator
            $table->timestamp('verified_at')->nullable();
            $table->text('officer_notes')->nullable(); // Catatan verifikasi / alasan penolakan
            $table->text('handover_notes')->nullable(); // Catatan serah terima fisik pos security
            $table->timestamp('handed_over_at')->nullable(); // Waktu serah terima fisik
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('claims');
    }
};
