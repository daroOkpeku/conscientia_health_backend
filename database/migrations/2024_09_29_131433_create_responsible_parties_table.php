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

        Schema::create('responsible_parties', function (Blueprint $table) {
            $table->id();
            $table->string("responsible_party_name")->nullable();
            $table->string("responsible_party_email")->nullable();
            $table->string("responsible_party_phone")->nullable();
            $table->string("responsible_party_relation")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('responsible_parties');
    }
};
