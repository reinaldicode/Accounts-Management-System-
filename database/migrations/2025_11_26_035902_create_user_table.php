<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user', function (Blueprint $table) {
            $table->id('iduser');
            $table->string('empid', 10)->unique();
            $table->string('username', 10)->unique();
            $table->string('password', 30);
            $table->string('firstname', 50);
            $table->string('middlename', 50)->nullable();
            $table->string('lastname', 50)->nullable();
            $table->string('slug', 50);
            $table->string('email_corp', 50)->nullable();
            $table->string('email_personal', 50)->nullable();
            $table->integer('extnum')->nullable();
            $table->string('mobnum1', 12)->nullable();
            $table->string('mobnum2', 12)->nullable();
            $table->string('address1', 200)->nullable();
            $table->string('address2', 200)->nullable();
            $table->string('state', 50)->nullable();
            $table->string('city', 50)->nullable();
            $table->string('country', 50)->nullable();
            $table->string('postcode', 10)->nullable();
            $table->string('deptid', 10)->nullable();
            $table->string('sectid', 10)->nullable();
            $table->string('titleid', 10)->nullable();
            $table->string('globalid', 20)->nullable();
            $table->integer('roleid');
            $table->string('active', 10)->nullable();
            $table->integer('failed_login_attempts')->default(0)->comment('Jumlah percobaan login gagal');
            $table->timestamp('locked_until')->nullable()->comment('Waktu lock berakhir jika account terkunci');
            $table->timestamp('last_login_at')->nullable()->comment('Waktu login terakhir');
            $table->dateTime('pwdexp')->nullable();
            $table->string('havepc', 10)->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('inactivated_at')->nullable();
            $table->softDeletes('deleted_at');
            
            $table->index('username', 'idx_username');
            $table->index('empid', 'idx_empid');
            $table->index('email_corp', 'idx_email_corp');
            $table->index('active', 'idx_active');
            $table->index('locked_until', 'idx_locked_until');
            $table->index('last_login_at', 'idx_last_login');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};