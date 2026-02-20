<?php

namespace App\Filament\Resources\Tutorias\Pages;

use App\Filament\Resources\Tutorias\TutoriaResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Carbon\Carbon;

class CreateTutoria extends CreateRecord
{
    protected static string $resource = TutoriaResource::class;

    public function getTitle(): string
    {
        return 'Registrar Tutoría';
    }

    public static function getNavigationLabel(): string
    {
        return 'Registrar '.static::$resource::getModelLabel();
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->validateLimitHoras($data);
        $this->validateFechas($data);

        return $data;
    }

    /* -------------------------
       Validaciones privadas
    ------------------------- */

    private function validateLimitHoras(array $data): void
    {
        $asignaturaId = $data['asignatura_id'] ?? $this->record?->asignatura_id;
        $horasNueva = $data['horas'] ?? $this->record?->horas ?? 0;

        if (!$asignaturaId || !$horasNueva) {
            return; // nada que validar
        }

        // Cargar asignatura con su actividad docente
        $asignatura = \App\Models\Asignatura::with('actividadDocente')->find($asignaturaId);
        if (!$asignatura || !$asignatura->actividadDocente) {
            return; // no se puede validar
        }

        $actividad = $asignatura->actividadDocente;

        // Sumar las horas de todas las tutorías existentes de esta actividad docente
        $totalHorasExistentes = \App\Models\Tutoria::query()
            ->whereHas('asignatura', fn($q) => $q->where('actividad_docente_id', $actividad->id))
            ->when($this->record?->id, fn($q) => $q->where('id', '!=', $this->record->id)) // excluir la tutoría actual si es edición
            ->sum('horas');

        $totalHoras = $totalHorasExistentes + $horasNueva;

        if ($totalHoras > $actividad->horas_tutorias) {
            Notification::make()
                ->title("No se puede registrar la tutoría: el total de horas ({$totalHoras}) excede el límite de tutorías de {$actividad->horas_tutorias} de esta actividad docente.")
                ->danger()
                ->send();

            $this->halt();
        }
    }


    private function validateFechas(array $data): void
    {
        $fechaInicio = $data['fecha'] ?? null;
        $horas = $data['horas'] ?? 0;
        $asignaturaId = $data['asignatura_id'] ?? null;

        if ($fechaInicio && $horas && $asignaturaId) {
            $fechaInicio = Carbon::parse($fechaInicio);
            $fechaFinal = $fechaInicio->copy()->addHours($horas);

            $conflicto = \App\Models\Tutoria::query()
                ->where('asignatura_id', $asignaturaId)
                ->where(function ($query) use ($fechaInicio, $fechaFinal) {
                    $query->whereBetween('fecha', [$fechaInicio, $fechaFinal])
                        ->orWhereRaw('? BETWEEN fecha AND DATE_ADD(fecha, INTERVAL horas HOUR)', [$fechaInicio]);
                })
                ->exists();

            if ($conflicto) {
                Notification::make()
                    ->title('La tutoría choca con otra tutoría existente en esa asignatura.')
                    ->danger()
                    ->send();

                $this->halt();
            }
        }
    }
}
