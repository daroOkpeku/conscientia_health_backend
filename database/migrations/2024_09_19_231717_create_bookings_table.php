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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string("firstname")->nullable();
            $table->string("lastname")->nullable();
            $table->string("state")->nullable();
            $table->string("doctor")->nullable();
            $table->string("email")->nullable();
            $table->string("phone")->nullable();
            $table->string("comment")->nullable();
            $table->string("visit_type")->nullable();
            //   "code",
            //    "is_used"
            $table->string("code")->nullable();
            $table->enum("is_used", ["nothing", 'active', 'used']);
            $table->string('country')->nullable();
            $table->string('legal_sex')->nullable();
            $table->string('dob')->nullable();
            $table->string('schedule_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
