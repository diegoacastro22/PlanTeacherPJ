<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PlanTrabajo extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'planes_trabajo';

    protected $fillable = [
        'user_id',
        'titulo',
        'descripcion',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Registrar las colecciones de media
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('plan_trabajo')
            ->useDisk('public')
            ->acceptsMimeTypes(['application/pdf']) // Solo PDF
            ->singleFile();
    }

    // Conversiones opcionales (por si quieres generar previews)
    public function registerMediaConversions(?Media $media = null): void
    {
        // Aquí puedes agregar conversiones si lo necesitas
        // Por ejemplo, generar thumbnails del PDF
    }
}
