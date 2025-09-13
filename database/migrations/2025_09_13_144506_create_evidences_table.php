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
        Schema::create('evidences', function (Blueprint $t) {
            $t->id();
            $t->foreignId('dispute_id')->constrained();
            $t->string('type');      // photo, video, doc
            $t->string('path');      // storage path
            $t->string('sha256',64)->nullable();
            $t->foreignId('uploaded_by')->constrained('users');
            $t->timestamp('uploaded_at')->useCurrent();
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evidences');
    }
};
