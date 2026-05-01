<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Make sure it says 'activity_user' here!
        Schema::create('activity_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained()->cascadeOnDelete(); // Fixed the $$ typo here
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('Acknowledged'); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_user');
    }
};