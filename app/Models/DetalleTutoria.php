<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleTutoria extends Model
{
    protected $table = 'detalle_tutorias';

    protected $fillable = [
        'tutoria_id',
        'estudiante_id',
        'asistio',
    ];

    protected $casts = [
        'asistio' => 'boolean',
    ];

    /* ------------------------------
       Relaciones
    -------------------------------*/

    public function tutoria(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Tutoria::class);
    }

    public function estudiante(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Estudiante::class);
    }
}
