<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('events', function (Blueprint $t) {
            $t->id();
            $t->foreignId('community_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete(); // creator
            $t->string('title');
            $t->text('description')->nullable();
            $t->timestamp('start_at');
            $t->timestamp('end_at')->nullable();
            $t->string('venue_name')->nullable();
            $t->string('address')->nullable();
            $t->string('city')->nullable();
            $t->string('state')->nullable();
            $t->string('pincode', 10)->nullable();
            $t->decimal('lat', 10, 7)->nullable();
            $t->decimal('lng', 10, 7)->nullable();
            $t->timestamps();
            $t->index(['start_at', 'city', 'state']);
        });

        Schema::create('event_attendees', function (Blueprint $t) {
            $t->id();
            $t->foreignId('event_id')->constrained()->cascadeOnDelete();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->string('status')->default('going'); // going|interested|not_going
            $t->timestamps();
            $t->unique(['event_id','user_id']);
        });
    }
};
