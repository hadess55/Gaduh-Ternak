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
        Schema::create('legal_basis', function (Blueprint $t) {
            $t->id();
            $t->string('code')->nullable();        // Kode internal / pasal
            $t->string('title');                   // Judul ringkas
            $t->enum('route', ['civil','criminal']);
            $t->text('article_ref')->nullable();   // Rujukan pasal
            $t->text('notes')->nullable();
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('legal_basis');
    }
};
