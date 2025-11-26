<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique()->comment('Nama permission');
            $table->text('description')->nullable()->comment('Deskripsi permission');
            $table->timestamps();
            
            $table->index('name', 'idx_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};