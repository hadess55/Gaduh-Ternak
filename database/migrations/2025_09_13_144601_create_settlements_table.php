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
        Schema::create('settlements', function (Blueprint $t) {
            $t->id();
            $t->foreignId('dispute_id')->constrained();
            $t->longText('agreement_text');
            $t->decimal('amount',14,2)->nullable();
            $t->date('due_date')->nullable();
            $t->enum('status',['draft','active','paid','default'])->default('draft');
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settlements');
    }
};
