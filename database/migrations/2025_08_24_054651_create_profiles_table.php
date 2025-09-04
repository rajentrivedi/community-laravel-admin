<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('profiles', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->string('full_name');
            $t->string('phone')->nullable();
            $t->string('gender')->nullable();
            $t->date('dob')->nullable();
            $t->string('city')->nullable();
            $t->string('state')->nullable();
            $t->string('pincode', 10)->nullable();
            $t->timestamps();
        });
    }
};
