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
        Schema::create('disputes', function (Blueprint $t) {
            $t->id();
            $t->foreignId('reporter_id')->constrained('users');
            $t->foreignId('defendant_id')->nullable()->constrained('users');
            $t->string('location');
            $t->timestamp('occurred_at');
            $t->text('description')->nullable();
            $t->enum('legal_route', ['civil','criminal','hybrid'])->default('civil');
            $t->enum('status', ['new','triaged','mediation','settled','escalated','closed'])->default('new');
            $t->decimal('damage_estimate', 14,2)->nullable();
            $t->foreignId('legal_basis_id')->nullable()->constrained('legal_basis');
            $t->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disputes');
    }
};
