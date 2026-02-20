<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Informe extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'informes';

    protected $fillable = [
        'user_id',
        'estudiante_id',
        'tipo_actividad',
        'observaciones',
    ];

    protected $casts = [
        'tipo_actividad' => 'string',
    ];

    /* ------------------------------
       Relaciones
    -------------------------------*/

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(Estudiante::class);
    }

    /* ------------------------------
       Media Library
    -------------------------------*/

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('informe_pdf')
            ->useDisk('public')
            ->acceptsMimeTypes(['application/pdf'])
            ->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        // Conversiones opcionales para thumbnails si lo necesitas
    }

    /* ------------------------------
       Helpers
    -------------------------------*/

    public function getTipoActividadLabelAttribute(): string
    {
        return match($this->tipo_actividad) {
            'orientacion_evaluacion_trabajos_grado' => 'Orientación y evaluación de trabajos de grado',
            'investigacion_aprobada' => 'Investigación aprobada',
            'proyeccion_social_registrada' => 'Proyección social registrada',
            'cooperacion_interinstitucional' => 'Cooperación interinstitucional',
            'crecimiento_personal_profesional' => 'Crecimiento personal y profesional',
            'actividades_administrativas' => 'Actividades administrativas',
            'otras_actividades' => 'Otras actividades',
            'compartidas_horas_semanales' => 'Compartidas con las horas semanales',
            default => $this->tipo_actividad,
        };
    }
}
