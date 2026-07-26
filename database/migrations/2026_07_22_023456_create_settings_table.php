<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {

            $table->id();

            $table->string('app_name')
                  ->default('Event Receipt');

            $table->string('company_name')
                  ->nullable();

            $table->string('app_url')
                  ->nullable();

            $table->string('timezone')
                  ->default('Asia/Jakarta');

            $table->string('logo')
                  ->nullable();

            $table->integer('qr_size')
                  ->default(250);

            $table->boolean('enable_webcam')
                  ->default(true);

            $table->boolean('auto_capture')
                  ->default(false);

            $table->integer('capture_delay')
                  ->default(2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
