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
        Schema::create('asignaturas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actividad_docente_id')->constrained('actividades_docente')->onDelete('cascade');

            $table->string('codigo');
            $table->string('nombre');
            $table->string('grupo', 2); // 01–20
            $table->string('facultad');

            $table->integer('limite_estudiantes');
            $table->integer('horas_practicas');
            $table->integer('horas_teoricas');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignaturas');
    }
};
