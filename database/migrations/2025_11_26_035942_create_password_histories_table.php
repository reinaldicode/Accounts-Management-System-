<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('password_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('Foreign key ke table user.iduser'); // ← FIXED
            $table->string('password', 255)->comment('Password lama');
            $table->timestamp('created_at')->useCurrent();
            
            $table->index('user_id', 'idx_user_id');
            $table->index('created_at', 'idx_created_at');
            
            $table->foreign('user_id')->references('iduser')->on('user')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('password_histories');
    }
};