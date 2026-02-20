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
        Schema::create('planes_trabajo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('titulo')->nullable();
            $table->text('descripcion')->nullable();
            $table->timestamps();

            // Un usuario solo puede tener un plan de trabajo
            $table->unique('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('planes_trabajo');
    }
};
