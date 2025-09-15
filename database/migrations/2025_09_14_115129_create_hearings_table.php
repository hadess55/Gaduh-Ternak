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
    Schema::create('hearings', function (Blueprint $t) {
      $t->id();
      $t->foreignId('dispute_id')->constrained();
      $t->foreignId('mediator_id')->nullable()->constrained('users');
      $t->dateTime('scheduled_at');
      $t->string('place')->nullable();
      $t->enum('result',['scheduled','success','failed','rescheduled'])->default('scheduled');
      $t->longText('minutes')->nullable(); // notulensi
      $t->timestamps();
    });
  }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hearings');
    }
};
