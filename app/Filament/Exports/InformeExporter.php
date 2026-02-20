<?php

namespace App\Filament\Exports;

use App\Models\Informe;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Number;

class InformeExporter extends Exporter
{
    protected static ?string $model = Informe::class;

    public static function modifyQuery(Builder $query): Builder
    {
        return $query->with(['estudiante', 'user', 'media']);
    }

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),

            ExportColumn::make('user.name')
                ->label('Docente'),

            ExportColumn::make('estudiante.nombre_completo')
                ->label('Estudiante'),

            ExportColumn::make('estudiante.codigo')
                ->label('Código Estudiante'),

            ExportColumn::make('tipo_actividad')
                ->label('Tipo Actividad')
                ->formatStateUsing(fn ($state) => match ($state) {
                    'orientacion_evaluacion_trabajos_grado' => 'Orientación y evaluación de trabajos de grado',
                    'investigacion_aprobada' => 'Investigación aprobada',
                    'proyeccion_social_registrada' => 'Proyección social registrada',
                    'cooperacion_interinstitucional' => 'Cooperación interinstitucional',
                    'crecimiento_personal_profesional' => 'Crecimiento personal y profesional',
                    'otras_actividades' => 'Otras actividades',
                    default => $state,
                }),

            ExportColumn::make('observaciones')
                ->label('Observaciones'),

            ExportColumn::make('fecha_creacion')
                ->state(fn (Model $record) => $record->created_at?->format('d/m/Y H:i'))
                ->label('Fecha Creación'),

            ExportColumn::make('pdf_url')
                ->label('URL del PDF')
                ->state(function (Model $record) {
                    return $record->hasMedia('informe_pdf')
                        ? $record->getFirstMedia('informe_pdf')->getUrl()
                        : 'Sin PDF';
                }),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'La exportación de informes se ha completado. ' .
            Number::format($export->successful_rows) . ' ' .
            str('registro')->plural($export->successful_rows) . ' exportados correctamente.';

        if ($failed = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failed) . ' fallaron al exportar.';
        }

        return $body;
    }
}
