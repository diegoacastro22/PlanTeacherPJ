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
        Schema::create('actividades_complementarias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->integer('horas_trabajos_grado')->default(0);
            $table->integer('horas_investigacion')->default(0);
            $table->integer('horas_proyeccion_social')->default(0);
            $table->integer('horas_cooperacion')->default(0);
            $table->integer('horas_crecimiento')->default(0);
            $table->integer('horas_administrativas')->default(0);
            $table->integer('horas_otras')->default(0);

            $table->integer('horas_compartidas')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actividad_complementarias');
    }
};
