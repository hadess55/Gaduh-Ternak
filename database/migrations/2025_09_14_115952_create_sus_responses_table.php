<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
    Schema::create('sus_responses', function (Blueprint $t) {
      $t->id();
      $t->foreignId('dispute_id')->constrained();   // kaitkan ke kasus
      $t->foreignId('user_id')->nullable()->constrained('users'); // yang mengisi (boleh null)
      $t->json('answers');        // 10 angka 1..5
      $t->unsignedTinyInteger('score')->default(0); // 0..100
      $t->timestamp('submitted_at')->nullable();
      $t->timestamps();
    });
  }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sus_responses');
    }
};
