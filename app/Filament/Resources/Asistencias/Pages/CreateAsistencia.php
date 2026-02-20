<?php

namespace App\Filament\Resources\Asistencias\Pages;

use App\Filament\Resources\Asistencias\AsistenciaResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateAsistencia extends CreateRecord
{
    protected static string $resource = AsistenciaResource::class;

    public function getTitle(): string
    {
        return 'Registrar Asistencia';
    }

    public static function getNavigationLabel(): string
    {
        return 'Registrar '.static::$resource::getModelLabel();
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $fecha = $data['fecha'] ?? null;
        $asignatura = $data['asignatura_id'] ?? null;
        if ($fecha && $asignatura) {
            $exists = static::$resource::getModel()::query()
                ->whereDate('fecha', $fecha)
                ->where('asignatura_id', $asignatura)
                ->exists();

            if ($exists) {
                Notification::make()
                    ->title('Ya existe una asistencia registrada para esta fecha y esta asignatura.')
                    ->danger()
                    ->send();

                $this->halt();
            }
        }

        return $data;
    }
}
