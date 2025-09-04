<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('communities', function (Blueprint $t) {
            $t->id();
            $t->string('name')->unique();
            $t->string('slug')->unique();
            $t->text('about')->nullable();
            $t->timestamps();
        });

        Schema::create('community_user', function (Blueprint $t) {
            $t->id();
            $t->foreignId('community_id')->constrained()->cascadeOnDelete();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->string('role')->default('member'); // convenience field
            $t->timestamps();
            $t->unique(['community_id','user_id']);
        });
    }
};
