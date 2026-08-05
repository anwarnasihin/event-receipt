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
        Schema::table('participant_receipts', function (Blueprint $table) {
            $table->string('browser')->nullable()->after('ip_address');
            $table->string('operating_system')->nullable()->after('browser');
            $table->text('user_agent')->nullable()->after('operating_system');
        });
    }

    public function down(): void
    {
        Schema::table('participant_receipts', function (Blueprint $table) {

            $table->dropColumn([
                'browser',
                'operating_system',
                'user_agent',
            ]);

        });
    }
};
