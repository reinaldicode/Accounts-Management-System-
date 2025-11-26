<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('Foreign key ke table user.iduser'); // ← FIXED
            $table->string('token', 500)->unique()->comment('JWT token ID');
            $table->string('refresh_token', 500)->nullable()->unique()->comment('Refresh token ID');
            $table->string('ip_address', 45)->nullable()->comment('IP address user');
            $table->text('user_agent')->nullable()->comment('Browser/device information');
            $table->timestamp('expires_at')->comment('Kapan token expired');
            $table->timestamp('last_activity_at')->useCurrent()->comment('Last activity timestamp');
            $table->timestamps();
            
            $table->index('user_id', 'idx_user_id');
            $table->index('token', 'idx_token');
            $table->index('expires_at', 'idx_expires_at');
            $table->index('last_activity_at', 'idx_last_activity');
            
            $table->foreign('user_id')->references('iduser')->on('user')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_sessions');
    }
};