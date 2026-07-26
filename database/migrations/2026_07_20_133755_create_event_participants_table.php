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
        Schema::create('event_participants', function (Blueprint $table) {

            $table->id();

            // Relasi Event
            $table->foreignId('event_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Kode Internal Sistem
            $table->string('code')->unique();

            // NIM / NIDN / Kode Staff / Kode Peserta
            $table->string('participant_code')->nullable();

            // Nama Peserta
            $table->string('name');

            // Email
            $table->string('email')->nullable();

            // Nomor HP
            $table->string('phone', 30)->nullable();

            // Home Base BINUS
            $table->string('campus')->nullable();

            // Fakultas
            $table->string('faculty')->nullable();

            // Jurusan / Department
            $table->string('department')->nullable();

            // Jabatan
            $table->string('position')->nullable();

            // Jenis Peserta
            $table->enum('participant_type', [
                'Dosen',
                'Staff',
                'Mahasiswa',
                'Guest'
            ])->default('Guest');

            // Peserta hasil input manual?
            $table->boolean('is_manual')->default(false);

            // Sudah menerima souvenir?
            $table->boolean('souvenir_status')->default(false);

            // Waktu menerima souvenir
            $table->timestamp('souvenir_taken_at')->nullable();

            // Catatan
            $table->text('notes')->nullable();

            // User yang membuat data
            $table->foreignId('created_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_participants');
    }
};
