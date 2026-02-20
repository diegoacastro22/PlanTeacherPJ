<?php

namespace App\Filament\Resources\ActividadDocentes\Pages;

use App\Filament\Resources\ActividadDocentes\ActividadDocenteResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditActividadDocente extends EditRecord
{
    protected static string $resource = ActividadDocenteResource::class;

    public static function shouldRegisterSpotlight(): bool
    {
        return false;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Editar Actividad del Docente';
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }
}
