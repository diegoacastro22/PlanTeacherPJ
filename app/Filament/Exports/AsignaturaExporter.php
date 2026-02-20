<?php

namespace App\Filament\Exports;

use App\Models\Asignatura;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Number;

class AsignaturaExporter extends Exporter
{
    protected static ?string $model = Asignatura::class;

    public static function modifyQuery(Builder $query): Builder
    {
        return $query->with(['actividadDocente.docente', 'estudiantes']);
    }

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),

            ExportColumn::make('actividad_docente_id')
                ->label('Actividad Docente')
                ->formatStateUsing(fn ($state) => $state ? "Actividad #{$state}" : 'Sin actividad'),

            ExportColumn::make('codigo')
                ->label('Código'),

            ExportColumn::make('nombre')
                ->label('Asignatura'),

            ExportColumn::make('grupo')
                ->label('Grupo')
                ->formatStateUsing(fn ($state) => $state ? strtoupper($state) : 'Sin grupo'),

            ExportColumn::make('facultad')
                ->label('Facultad'),

            ExportColumn::make('limite_estudiantes')
                ->label('Cupo Máximo')
                ->formatStateUsing(fn ($state) => $state > 0 ? "{$state} estudiantes" : 'Sin límite'),

            ExportColumn::make('horas_practicas')
                ->label('Horas Prácticas')
                ->formatStateUsing(fn ($state) => "{$state} horas"),

            ExportColumn::make('horas_teoricas')
                ->label('Horas Teóricas')
                ->formatStateUsing(fn ($state) => "{$state} horas"),

            ExportColumn::make('created_at')
                ->label('Creado el')
                ->formatStateUsing(fn ($state) => $state ? $state->format('d/m/Y H:i') : 'N/A'),

            ExportColumn::make('updated_at')
                ->label('Actualizado el')
                ->formatStateUsing(fn ($state) => $state ? $state->format('d/m/Y H:i') : 'N/A'),

            // Información del docente
            ExportColumn::make('actividadDocente.docente.email')
                ->label('Email del Docente')
                ->default('Sin email'),

            ExportColumn::make('actividadDocente.docente.name')
                ->label('Nombre del Docente')
                ->default('Sin docente'),

            // Estado del cupo
            ExportColumn::make('estado_cupo')
                ->label('Estado del Cupo')
                ->state(function (Model $record): string {
                    $limite = $record->limite_estudiantes ?? 0;

                    if ($limite === 0) {
                        return 'Sin límite establecido';
                    }

                    $inscritos = $record->estudiantes->count();

                    if ($inscritos >= $limite) {
                        return 'CUPO LLENO';
                    }

                    $porcentaje = ($inscritos / $limite) * 100;

                    if ($porcentaje >= 80) {
                        return 'CUPO CASI LLENO';
                    } elseif ($inscritos === 0) {
                        return 'SIN INSCRITOS';
                    }

                    return 'DISPONIBLE';
                }),

            // Cupos disponibles
            ExportColumn::make('cupos_disponibles')
                ->label('Cupos Disponibles')
                ->state(function (Model $record): string {
                    $limite = $record->limite_estudiantes ?? 0;

                    if ($limite === 0) {
                        return 'Ilimitado';
                    }

                    $inscritos = $record->estudiantes->count();
                    $disponibles = max(0, $limite - $inscritos);

                    return "{$disponibles} de {$limite}";
                }),

            // Estudiantes inscritos
            ExportColumn::make('estudiantes_inscritos')
                ->label('Estudiantes Inscritos')
                ->state(function (Model $record): int {
                    return $record->estudiantes->count();
                }),

            // Tipo de curso
            ExportColumn::make('tipo_curso')
                ->label('Tipo de Curso')
                ->state(function (Model $record): string {
                    $practicas = $record->horas_practicas ?? 0;
                    $teoricas = $record->horas_teoricas ?? 0;

                    if ($practicas > 0 && $teoricas > 0) {
                        return 'Teórico-Práctico';
                    } elseif ($practicas > 0) {
                        return 'Práctico';
                    } elseif ($teoricas > 0) {
                        return 'Teórico';
                    }

                    return 'No definido';
                }),

            // Total de horas
            ExportColumn::make('total_horas')
                ->label('Total Horas')
                ->state(function (Model $record): string {
                    $total = ($record->horas_practicas ?? 0) + ($record->horas_teoricas ?? 0);
                    return "{$total} horas";
                }),

            // Porcentaje de ocupación
            ExportColumn::make('porcentaje_ocupacion')
                ->label('% Ocupación')
                ->state(function (Model $record): string {
                    $limite = $record->limite_estudiantes ?? 0;

                    if ($limite === 0) {
                        return 'N/A';
                    }

                    $inscritos = $record->estudiantes->count();
                    $porcentaje = round(($inscritos / $limite) * 100, 2);

                    return "{$porcentaje}%";
                }),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'La exportación de asignaturas se ha completado. ' .
            Number::format($export->successful_rows) . ' ' .
            str('registro')->plural($export->successful_rows) . ' exportados correctamente.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' .
                str('registro')->plural($failedRowsCount) . ' fallaron al exportar.';
        }

        return $body;
    }
}
