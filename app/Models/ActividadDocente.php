<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActividadDocente extends Model
{
    protected $table = 'actividades_docente';

    protected $fillable = [
        'user_id',
        'total_asignaturas',
        'total_grupos',
        'total_estudiantes',
        'horas_docencia_directa',
        'horas_tutorias',
        'horas_preparacion',
        'max_asignaturas',
    ];

    protected $casts = [
        'total_asignaturas' => 'integer',
        'total_grupos' => 'integer',
        'total_estudiantes' => 'integer',
        'horas_docencia_directa' => 'integer',
        'horas_tutorias' => 'integer',
        'horas_preparacion' => 'integer',
        'max_asignaturas' => 'integer',
    ];

    /* ------------------------------
       Relaciones
    -------------------------------*/

    public function docente()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function asignaturas()
    {
        return $this->hasMany(Asignatura::class);
    }

    public function actividadesComplementarias()
    {
        return $this->hasOne(ActividadComplementaria::class, 'user_id', 'user_id');
    }

    /* ------------------------------
       Eventos del modelo
    -------------------------------*/

    protected static function booted()
    {
        // Actualizar contador de asignaturas automáticamente
        static::saving(function ($actividad) {
            $actividad->total_asignaturas = $actividad->asignaturas()->count();
        });
    }
}
