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
        Schema::create('preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('preferred_age_min')->nullable();
            $table->integer('preferred_age_max')->nullable();
            $table->enum('preferred_gender', ['male', 'female', 'other'])->nullable();
            $table->string('preferred_religion')->nullable();
            $table->string('preferred_caste')->nullable();
            $table->integer('preferred_income_min')->nullable();
            $table->integer('preferred_income_max')->nullable();
            $table->string('preferred_education')->nullable();
            $table->string('preferred_location')->nullable();
            $table->string('preferred_occupation')->nullable();
            $table->float('age_weight')->default(1.0);
            $table->float('gender_weight')->default(1.0);
            $table->float('religion_weight')->default(1.0);
            $table->float('caste_weight')->default(1.0);
            $table->float('income_weight')->default(1.0);
            $table->float('education_weight')->default(1.0);
            $table->float('location_weight')->default(1.0);
            $table->float('occupation_weight')->default(1.0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preferences');
    }
};
