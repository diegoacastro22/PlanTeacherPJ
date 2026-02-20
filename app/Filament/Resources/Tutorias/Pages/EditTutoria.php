<?php

namespace App\Filament\Resources\Tutorias\Pages;

use App\Filament\Resources\Tutorias\TutoriaResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Carbon\Carbon;

class EditTutoria extends EditRecord
{
    protected static string $resource = TutoriaResource::class;

    protected $listeners = ['$refresh' => '$refresh'];

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }

    public function getTitle(): string
    {
        return 'Editar Tutoría';
    }

    public static function shouldRegisterSpotlight(): bool
    {
        return false;
    }

    protected function getSaveFormAction(): Action
    {
        return parent::getSaveFormAction()
            ->disabled(fn () => $this->record->finalizada);
    }

    protected function beforeSave(): void
    {
        $data = $this->form->getState();

        $this->validateLimitHoras($data);
        $this->validateFechas($data);
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

        // Sumar las horas de todas las tutorías existentes de esta actividad docente, excluyendo la que se está editando
        $totalHorasExistentes = \App\Models\Tutoria::query()
            ->whereHas('asignatura', fn($q) => $q->where('actividad_docente_id', $actividad->id))
            ->where('id', '!=', $this->record->id) // excluir el registro actual
            ->sum('horas');

        $totalHoras = $totalHorasExistentes + $horasNueva;

        if ($totalHoras > $actividad->horas_tutorias) {
            Notification::make()
                ->title("No se puede guardar la tutoría: el total de horas ({$totalHoras}) excede el límite de tutorías de {$actividad->horas_tutorias}.")
                ->danger()
                ->send();

            $this->halt();
        }
    }


    private function validateFechas(array $data): void
    {
        $fechaInicio = $data['fecha'] ?? $this->record->fecha;
        $horas = $data['horas'] ?? $this->record->horas;
        $asignaturaId = $data['asignatura_id'] ?? $this->record->asignatura_id;

        if ($fechaInicio && $horas && $asignaturaId) {
            $fechaInicio = Carbon::parse($fechaInicio);
            $fechaFinal = $fechaInicio->copy()->addHours($horas);

            $conflicto = \App\Models\Tutoria::query()
                ->where('asignatura_id', $asignaturaId)
                ->where('id', '!=', $this->record->id)
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
