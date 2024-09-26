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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('drchrono_id')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('specialty')->nullable();
            $table->string('job_title')->nullable();
            $table->string('suffix')->nullable();
            $table->string('website')->nullable();
            $table->string('home_phone')->nullable();
            $table->string('office_phone')->nullable();
            $table->string('cell_phone')->nullable();
            $table->string('country')->nullable();
            $table->string('timezone')->nullable();
            $table->string('npi_number')->nullable();
            $table->string('group_npi_number')->nullable();
            $table->string('practice_group')->nullable();
            $table->string('practice_group_name')->nullable();
            $table->longText('profile_picture')->nullable();
            $table->boolean('is_account_suspended')->default(False);
            $table->string('states')->nullable();
            $table->bigInteger('age_start')->nullable();
            $table->bigInteger('age_end')->nullable();
            $table->boolean('is_therapist')->default(False);
            $table->boolean('is_new_patient')->default(False);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
