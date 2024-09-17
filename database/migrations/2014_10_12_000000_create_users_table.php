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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('otp')->nullable();
            $table->enum('otp_status', ['active', 'used', 'nothing']);
            $table->boolean('confirm_status')->default(false);
            $table->string('password');
            $table->enum('user_type', ['user', 'admin', 'customer_care']);
            $table->string('captcha');
            $table->string('is_accepted')->nullable();
            $table->string('api_token');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
