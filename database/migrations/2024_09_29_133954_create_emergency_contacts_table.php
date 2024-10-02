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
        // "emergency_contact_name",
        // "emergency_contact_phone",
        // "emergency_contact_relation"

        Schema::create('emergency_contacts', function (Blueprint $table) {
            $table->id();
            $table->string("emergency_contact_name")->nullable();
            $table->string("emergency_contact_phone")->nullable();
            $table->string("emergency_contact_relation")->nullable();
            $table->unsignedBigInteger("user_id");
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emergency_contacts');
    }
};
