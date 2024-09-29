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
        Schema::create('primary_insurances', function (Blueprint $table) {
            $table->id();
          $table->string("photo_front")->nullable();
          $table->string("photo_back")->nullable();
           $table->string("insurance_group_number")->nullable();
            $table->string("insurance_company")->nullable();
             $table->string("insurance_payer_id")->nullable();
              $table->string("insurance_plan_type")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('primary_insurances');
    }
};
