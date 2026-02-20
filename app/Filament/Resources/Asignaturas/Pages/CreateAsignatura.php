<?php

namespace App\Filament\Resources\Asignaturas\Pages;

use App\Filament\Resources\Asignaturas\AsignaturaResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateAsignatura extends CreateRecord
{
    protected static string $resource = AsignaturaResource::class;

    public function getTitle(): string
    {
        return 'Crear Asignatura';
    }

    public static function getNavigationLabel(): string
    {
        return 'Registrar '.static::$resource::getModelLabel();
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['actividad_docente_id'] = auth()->user()->actividadDocente->id;

        return $data;
    }

    protected function beforeCreate(): void
    {
        $this->validateLimits();
    }

    protected function validateLimits(): void
    {
        $user = auth()->user();
        $data = $this->data;

        $this->validateHorasDocencia($user, $data);
        $this->validateLimiteEstudiantes($user, $data);
        $this->validateMaxAsignaturas($user);
    }

    protected function validateHorasDocencia($user, $data): void
    {
        $horasActuales = $user->asignaturas()->sum('horas_practicas')
            + $user->asignaturas()->sum('horas_teoricas');

        $horasNuevas = ($data['horas_practicas'] ?? 0) + ($data['horas_teoricas'] ?? 0);
        $horasTotales = $horasActuales + $horasNuevas;
        $limite = $user->actividadDocente->horas_docencia_directa;

        if ($horasTotales > $limite) {
            $this->notifyAndHalt(
                'Límite de horas excedido',
                "No puedes añadir esta asignatura. Límite: {$limite}h | Actuales: {$horasActuales}h | Con esta: {$horasTotales}h"
            );
        }
    }

    protected function validateLimiteEstudiantes($user, $data): void
    {
        $estudiantesActuales = $user->asignaturas()->sum('limite_estudiantes');
        $estudiantesNuevos = $data['limite_estudiantes'] ?? 0;
        $estudiantesTotales = $estudiantesActuales + $estudiantesNuevos;
        $limite = $user->actividadDocente->total_estudiantes;

        if ($estudiantesTotales > $limite) {
            $this->notifyAndHalt(
                'Límite de estudiantes excedido',
                "No puedes añadir esta asignatura. Límite: {$limite} | Actuales: {$estudiantesActuales} | Con esta: {$estudiantesTotales}"
            );
        }
    }

    protected function validateMaxAsignaturas($user): void
    {
        $asignaturasCount = $user->asignaturas()->count();
        $limite = $user->actividadDocente->max_asignaturas;

        if ($asignaturasCount >= $limite) {
            $this->notifyAndHalt(
                'Límite de asignaturas alcanzado',
                "Has alcanzado el máximo de {$limite} asignaturas. Actualmente tienes {$asignaturasCount}."
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
