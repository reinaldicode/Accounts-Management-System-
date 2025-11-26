<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_system_access', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('Foreign key ke table user.iduser'); // ← FIXED
            $table->foreignId('system_id')->constrained('systems')->onDelete('cascade');
            $table->foreignId('role_id')->constrained('roles')->onDelete('restrict');
            $table->boolean('is_active')->default(1)->comment('1=active, 0=inactive');
            $table->timestamp('granted_at')->useCurrent()->comment('Kapan akses diberikan');
            $table->unsignedBigInteger('granted_by')->nullable()->comment('User ID yang memberikan akses'); // ← FIXED
            $table->timestamp('expires_at')->nullable()->comment('Kapan akses expired');
            $table->timestamps();
            
            $table->unique(['user_id', 'system_id'], 'unique_user_system');
            $table->index('user_id', 'idx_user_id');
            $table->index('system_id', 'idx_system_id');
            $table->index('role_id', 'idx_role_id');
            $table->index('is_active', 'idx_is_active');
            $table->index('expires_at', 'idx_expires_at');
            
            $table->foreign('user_id')->references('iduser')->on('user')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_system_access');
    }
};