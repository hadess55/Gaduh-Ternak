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
        Schema::create('farmers', function (Blueprint $t) {
        $t->id();
        $t->string('nama');
        $t->string('nik', 20)->unique();
        $t->string('telp')->nullable();
        $t->text('alamat')->nullable();
        $t->string('desa');
        $t->string('kecamatan');
        $t->string('jenis_ternak');
        $t->unsignedInteger('jumlah_ternak')->default(0);
        $t->enum('status', ['pending','validated','rejected'])->default('pending');
        $t->foreignId('validated_by')->nullable()->constrained('users');
        $t->timestamp('validated_at')->nullable();
        $t->text('catatan')->nullable();
        $t->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmers');
    }
};
