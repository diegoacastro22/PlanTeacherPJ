<?php

namespace App\Filament\Resources\ActividadDocentes\Pages;

use App\Filament\Resources\ActividadDocentes\ActividadDocenteResource;
use Filament\Resources\Pages\CreateRecord;

class CreateActividadDocente extends CreateRecord
{
    protected static string $resource = ActividadDocenteResource::class;

    public function getTitle(): string
    {
        return 'Registrar Actividad del Docente';
    }

    public static function getNavigationLabel(): string
    {
        return 'Registrar '.static::$resource::getModelLabel();
    }

    public function mount(): void
    {
        $user = auth()->user();

        if ($user && $user->actividadDocente) {
            $this->redirect(
                ActividadDocenteResource::getUrl('edit', [
                    'record' => $user->actividadDocente->id,
                ])
            );
        }
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }
}
