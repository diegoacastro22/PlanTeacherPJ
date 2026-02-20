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
        Schema::create('informes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('estudiante_id')->constrained('estudiantes')->onDelete('cascade');
            $table->enum('tipo_actividad', [
                'orientacion_evaluacion_trabajos_grado',
                'investigacion_aprobada',
                'proyeccion_social_registrada',
                'cooperacion_interinstitucional',
                'crecimiento_personal_profesional',
                'actividades_administrativas',
                'otras_actividades',
                'compartidas_horas_semanales',
            ]);
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('informes');
    }
};
