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
        Schema::table('estudiantes', function (Blueprint $table) {
            $table->dropForeign(['asignatura_id']);
            $table->dropColumn('asignatura_id');
        });
    }

    public function down()
    {
        Schema::table('estudiantes', function (Blueprint $table) {
            $table->foreignId('asignatura_id')->constrained('asignaturas')->onDelete('cascade');
        });
    }
};
