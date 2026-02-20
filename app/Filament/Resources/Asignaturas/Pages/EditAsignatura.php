<?php

namespace App\Filament\Resources\Asignaturas\Pages;

use App\Filament\Resources\Asignaturas\AsignaturaResource;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditAsignatura extends EditRecord
{
    protected static string $resource = AsignaturaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Editar Asignatura';
    }

    public static function shouldRegisterSpotlight(): bool
    {
        return false;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['actividad_docente_id'] = auth()->user()->actividadDocente->id;

        return $data;
    }

    protected function beforeSave(): void
    {
        $this->validateLimits();
    }

    protected function validateLimits(): void
    {
        $user = auth()->user();
        $data = $this->data;
        $asignaturaActual = $this->record;

        // Una sola query, especificando la tabla
        $otrasAsignaturas = $user->asignaturas()
            ->where('asignaturas.id', '!=', $asignaturaActual->id)
            ->get();

        $this->validateHorasDocencia($otrasAsignaturas, $data, $user);
        $this->validateLimiteEstudiantes($otrasAsignaturas, $data, $user);
    }

    protected function validateHorasDocencia($otrasAsignaturas, $data, $user): void
    {
        $horasOtrasAsignaturas = $otrasAsignaturas->sum('horas_practicas')
            + $otrasAsignaturas->sum('horas_teoricas');

        $horasNuevas = ($data['horas_practicas'] ?? 0) + ($data['horas_teoricas'] ?? 0);
        $horasTotales = $horasOtrasAsignaturas + $horasNuevas;
        $limite = $user->actividadDocente->horas_docencia_directa;

        if ($horasTotales > $limite) {
            $this->notifyAndHalt(
                'Límite de horas excedido',
                "No puedes actualizar esta asignatura. Límite: {$limite}h | Otras asignaturas: {$horasOtrasAsignaturas}h | Con estos cambios: {$horasTotales}h"
            );
        }
    }

    protected function validateLimiteEstudiantes($otrasAsignaturas, $data, $user): void
    {
        $estudiantesOtrasAsignaturas = $otrasAsignaturas->sum('limite_estudiantes');
        $estudiantesNuevos = $data['limite_estudiantes'] ?? 0;
        $estudiantesTotales = $estudiantesOtrasAsignaturas + $estudiantesNuevos;
        $limite = $user->actividadDocente->total_estudiantes;

        if ($estudiantesTotales > $limite) {
            $this->notifyAndHalt(
                'Límite de estudiantes excedido',
                "No puedes actualizar esta asignatura. Límite: {$limite} | Otras asignaturas: {$estudiantesOtrasAsignaturas} | Con estos cambios: {$estudiantesTotales}"
            );
        }
    }

    protected function notifyAndHalt(string $title, string $body): void
    {
        Notification::make()
            ->danger()
            ->title($title)
            ->body($body)
            ->persistent()
            ->send();

        $this->halt();
    }
}
