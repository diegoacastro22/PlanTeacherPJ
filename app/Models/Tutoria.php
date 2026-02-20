<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Tutoria extends Model
{
    protected $table = 'tutorias';

    protected $fillable = [
        'asignatura_id',
        'fecha',
        'horas',
        'finalizada',
        'observaciones',
        'estudiante_id',
        'asistio',
    ];

    protected $casts = [
        'fecha' => 'datetime',
        'horas' => 'integer',
    ];

    /* ------------------------------
       Relaciones
    -------------------------------*/

    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class);
    }

    public function detalles(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DetalleTutoria::class);
    }

    protected function fechaFinalizacion(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->fecha && $this->horas
                ? $this->fecha->copy()->addHours($this->horas)
                : null
        );
    }
}
