<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique()->comment('Nama role');
            $table->text('description')->nullable()->comment('Deskripsi role');
            $table->string('department', 100)->nullable()->comment('Department/Departemen');
            $table->string('section', 100)->nullable()->comment('Section/Seksi');
            $table->string('title', 100)->nullable()->comment('Jabatan');
            $table->timestamps();
            
            $table->index('name', 'idx_name');
            $table->index('department', 'idx_department');
            $table->index('section', 'idx_section');
            $table->index('title', 'idx_title');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};