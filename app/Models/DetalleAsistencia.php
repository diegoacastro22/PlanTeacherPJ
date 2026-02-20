<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleAsistencia extends Model
{
    protected $table = 'detalle_asistencias';

    protected $fillable = [
        'asistencia_id',
        'estudiante_id',
        'asistio',
    ];

    protected $casts = [
        'asistio' => 'boolean',
    ];

    /* ------------------------------
       Relaciones
    -------------------------------*/

    public function asistencia(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Asistencia::class);
    }

    public function estudiante(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Estudiante::class);
    }
}
