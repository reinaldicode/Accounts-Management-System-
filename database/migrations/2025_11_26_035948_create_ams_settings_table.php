<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ams_settings', function (Blueprint $table) {
            $table->id();
            $table->string('setting_key', 100)->unique()->comment('Key setting');
            $table->text('setting_value')->nullable()->comment('Value setting');
            $table->enum('setting_type', ['string', 'integer', 'boolean', 'json'])->default('string')->comment('Tipe data setting');
            $table->text('description')->nullable()->comment('Deskripsi setting');
            $table->boolean('is_public')->default(0)->comment('1=public, 0=private');
            $table->timestamps();
            
            $table->index('setting_key', 'idx_setting_key');
            $table->index('is_public', 'idx_is_public');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ams_settings');
    }
};