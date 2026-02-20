<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    protected $table = 'estudiantes';

    protected $fillable = [
        'asignatura_id',
        'codigo',
        'nombre_completo',
        'correo',
    ];

    /* ------------------------------
       Relaciones
    -------------------------------*/

    public function asignaturas(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Asignatura::class, 'asignatura_estudiante')
            ->withTimestamps();
    }

    public function detalleAsistencias()
    {
        return $this->hasMany(DetalleAsistencia::class);
    }
}
