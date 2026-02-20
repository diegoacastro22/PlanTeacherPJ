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
        Schema::table('tutorias', function (Blueprint $table) {
            $table->boolean('finalizada')->default(false);
            $table->text('observaciones')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('tutorias', function (Blueprint $table) {
            $table->dropColumn(['finalizada', 'observaciones']);
        });
    }
};
