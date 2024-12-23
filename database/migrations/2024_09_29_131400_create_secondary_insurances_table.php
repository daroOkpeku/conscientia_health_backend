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
        Schema::create('secondary_insurances', function (Blueprint $table) {
            $table->id();
            $table->mediumText("photo_front")->nullable();
            $table->mediumText("photo_back")->nullable();
             $table->string("insurance_group_number")->nullable();
              $table->string("insurance_company")->nullable();
               $table->string("insurance_payer_id")->nullable();
                $table->string("insurance_plan_type")->nullable();
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
        Schema::dropIfExists('secondary_insurances');
    }
};
