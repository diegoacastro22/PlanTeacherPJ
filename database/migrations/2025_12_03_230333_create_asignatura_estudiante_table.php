<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('asignatura_estudiante', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asignatura_id')->constrained('asignaturas')->onDelete('cascade');
            $table->foreignId('estudiante_id')->constrained('estudiantes')->onDelete('cascade');
            $table->timestamps();

            // Prevenir duplicados: un estudiante no puede estar 2 veces en la misma asignatura
            $table->unique(['asignatura_id', 'estudiante_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('asignatura_estudiante');
    }
};
