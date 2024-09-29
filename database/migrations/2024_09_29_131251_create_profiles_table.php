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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string("middle_name")->nullable();
            $table->string("nick_name")->nullable();
            $table->string('email')->nullable();
            $table->string("state")->nullable();
            $table->string("date_of_birth")->nullable();
            $table->string("home_phone")->nullable();
            $table->string("patient_photo")->nullable();
            $table->string("office_phone")->nullable();
            $table->string("cell_phone")->nullable();
            $table->string("address")->nullable();
            $table->string("zip_code")->nullable();
            $table->string("gender")->nullable();
            $table->string("chart_id")->nullable();
            $table->string("race")->nullable();
            $table->string("ethnicity")->nullable();
            $table->string("doctor")->nullable();
            $table->enum("patient_status", ["A", "I", "D"])->nullable();
            $table->string("preferred_language")->nullable();
            $table->bigIncrements("user_id");
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
