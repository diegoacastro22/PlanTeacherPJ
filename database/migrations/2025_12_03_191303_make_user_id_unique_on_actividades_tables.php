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
        Schema::table('actividades_docente', function (Blueprint $table) {
            // elimina si ya existe índice no-unique antes de crear único
            if (! Schema::hasColumn('actividades_docente', 'user_id')) {
                return;
            }
            $table->unique('user_id');
        });

        Schema::table('actividades_complementarias', function (Blueprint $table) {
            if (! Schema::hasColumn('actividades_complementarias', 'user_id')) {
                return;
            }
            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('actividades_docente', function (Blueprint $table) {
            $table->dropUnique(['user_id']);
        });

        Schema::table('actividades_complementarias', function (Blueprint $table) {
            $table->dropUnique(['user_id']);
        });
    }
};
