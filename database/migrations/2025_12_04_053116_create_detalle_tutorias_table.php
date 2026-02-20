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
        Schema::create('detalle_tutorias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tutoria_id')->constrained()->onDelete('cascade');
            $table->foreignId('estudiante_id')->constrained()->onDelete('cascade');
            $table->boolean('asistio')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_tutorias');
    }
};
