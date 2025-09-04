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
        Schema::table('family_members', function (Blueprint $table) {
            // Add the missing columns if they don't exist
            if (!Schema::hasColumn('family_members', 'relationship')) {
                $table->string('relationship')->nullable(); // e.g., Father, Mother, Spouse, Child, Sibling
            }
            if (!Schema::hasColumn('family_members', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable();
            }
            if (!Schema::hasColumn('family_members', 'phone')) {
                $table->string('phone')->nullable();
            }
            if (!Schema::hasColumn('family_members', 'email')) {
                $table->string('email')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('family_members', function (Blueprint $table) {
            if (Schema::hasColumn('family_members', 'relationship')) {
                $table->dropColumn('relationship');
            }
            if (Schema::hasColumn('family_members', 'date_of_birth')) {
                $table->dropColumn('date_of_birth');
            }
            if (Schema::hasColumn('family_members', 'phone')) {
                $table->dropColumn('phone');
            }
            if (Schema::hasColumn('family_members', 'email')) {
                $table->dropColumn('email');
            }
        });
    }
};
