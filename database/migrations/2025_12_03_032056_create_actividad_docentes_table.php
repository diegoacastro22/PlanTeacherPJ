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
        Schema::create('actividades_docente', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Datos calculados
            $table->integer('total_asignaturas')->default(0);
            $table->integer('total_grupos')->default(0);
            $table->integer('total_estudiantes')->default(0);

            // Horas
            $table->integer('horas_docencia_directa')->default(0);
            $table->integer('horas_tutorias')->default(0);
            $table->integer('horas_preparacion')->default(0);

            // Límite de asignaciones
            $table->integer('max_asignaturas')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actividad_docentes');
    }
};
