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

        Schema::create('employers', function (Blueprint $table) {
            $table->id();
            $table->string("employer_name")->nullable();
            $table->string("employer_state")->nullable();
            $table->string("employer_city")->nullable();
            $table->string("employer_zip_code")->nullable();
            $table->string("employer_address")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employers');
    }
};