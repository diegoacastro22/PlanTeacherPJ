<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActividadComplementaria extends Model
{
    protected $table = 'actividades_complementarias';

    protected $fillable = [
        'user_id',
        'horas_trabajos_grado',
        'horas_investigacion',
        'horas_proyeccion_social',
        'horas_cooperacion',
        'horas_crecimiento',
        'horas_administrativas',
        'horas_otras',
        'horas_compartidas',
    ];

    protected $casts = [
        'horas_trabajos_grado' => 'integer',
        'horas_investigacion' => 'integer',
        'horas_proyeccion_social' => 'integer',
        'horas_cooperacion' => 'integer',
        'horas_crecimiento' => 'integer',
        'horas_administrativas' => 'integer',
        'horas_otras' => 'integer',
        'horas_compartidas' => 'integer',
    ];

    /* ------------------------------
       Relaciones
    -------------------------------*/

    public function docente()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
