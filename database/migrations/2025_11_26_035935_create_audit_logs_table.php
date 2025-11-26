<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->comment('Foreign key ke table user.iduser'); // ← FIXED
            $table->unsignedBigInteger('system_id')->nullable()->comment('Foreign key ke table systems');
            $table->string('action', 100)->comment('Aksi yang dilakukan');
            $table->string('ip_address', 45)->nullable()->comment('IP address user');
            $table->text('user_agent')->nullable()->comment('Browser/device information');
            $table->enum('status', ['success', 'failed', 'denied'])->default('success')->comment('Status aksi');
            $table->json('additional_data')->nullable()->comment('Data tambahan dalam format JSON');
            $table->timestamp('created_at')->useCurrent();
            
            $table->index('user_id', 'idx_user_id');
            $table->index('system_id', 'idx_system_id');
            $table->index('action', 'idx_action');
            $table->index('status', 'idx_status');
            $table->index('created_at', 'idx_created_at');
            $table->index(['user_id', 'action'], 'idx_user_action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};