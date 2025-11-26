<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('systems', function (Blueprint $table) {
            $table->id();
            $table->string('system_code', 50)->unique()->comment('Kode unik sistem');
            $table->string('system_name', 255)->comment('Nama tampilan sistem');
            $table->string('system_url', 500)->comment('URL sistem');
            $table->string('api_key', 255)->unique()->comment('API key untuk validasi request');
            $table->text('description')->nullable()->comment('Deskripsi sistem');
            $table->boolean('is_active')->default(1)->comment('1=active, 0=inactive');
            $table->timestamps();
            
            $table->index('system_code', 'idx_system_code');
            $table->index('is_active', 'idx_is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('systems');
    }
};