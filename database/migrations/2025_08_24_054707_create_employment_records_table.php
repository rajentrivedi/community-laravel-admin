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
        Schema::create('employment_records', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            
            // Employment details
            $table->string('organization')->nullable();
            $table->string('job_title')->nullable();
            $table->string('industry')->nullable();       // e.g., IT, Finance, Education
            $table->string('employment_type')->nullable(); // Full-time, Part-time, Self-employed, Business, Govt, PSU, etc.

            // Employment timeline
            $table->date('start_date_employment')->nullable();
            $table->date('end_date_employment')->nullable();
            $table->boolean('is_current')->default(false);

            // Employment compensation (keep flexible for India + abroad)
            $table->decimal('annual_income', 12, 2)->nullable(); // gross annual
            $table->string('currency', 3)->default('INR');       // ISO code

            // Employment location
            $table->string('city_employment')->nullable();
            $table->string('state_employment')->nullable();
            $table->string('country_employment')->nullable()->default('India');

            // Education details
            $table->string('degree_level')->index(); // e.g. "10th", "12th", "Diploma", "Bachelors", "Masters", "PhD", "Other"
            $table->string('field_of_study')->nullable(); // e.g. "Computer Science", "Commerce"
            $table->string('institution')->nullable();    // school/college/institute
            $table->string('board_university')->nullable(); // CBSE/GSEB/GTU/etc.

            // Education timeline
            $table->date('start_date_study')->nullable();
            $table->date('end_date_study')->nullable();
            $table->boolean('currently_studying')->default(false);

            // Education details
            $table->string('grade')->nullable();   // percentage/CGPA
            $table->string('city_study')->nullable();
            $table->string('state_study')->nullable();
            $table->string('country_study')->nullable()->default('India');

            $table->index(['industry', 'employment_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employment_records');
    }
};
