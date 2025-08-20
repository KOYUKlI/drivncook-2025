<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('franchise_applications', function (Blueprint $t) {
            $t->id();
            $t->string('full_name');
            $t->string('email')->index();
            $t->string('phone')->nullable();
            $t->string('city')->nullable();
            $t->integer('budget')->nullable();
            $t->text('experience')->nullable();
            $t->text('motivation');
            $t->enum('status', ['pending','accepted','rejected'])->default('pending');
            $t->timestamp('reviewed_at')->nullable();
            $t->timestamps();
            $t->unique(['email','status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('franchise_applications');
    }
};
