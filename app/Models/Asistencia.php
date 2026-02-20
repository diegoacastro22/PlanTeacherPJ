<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    protected $table = 'asistencias';

    protected $fillable = [
        'asignatura_id',
        'fecha',
        'finalizada',
        'estudiante_id',
        'asistio',
    ];

    protected $casts = [
        'fecha' => 'date',
        'finalizada' => 'boolean',
    ];

    /* ------------------------------
       Relaciones
    -------------------------------*/

    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class);
    }

    public function detalles()
    {
        return $this->hasMany(DetalleAsistencia::class);
    }
}
