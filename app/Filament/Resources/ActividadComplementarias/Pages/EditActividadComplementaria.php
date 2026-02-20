<?php

namespace App\Filament\Resources\ActividadComplementarias\Pages;

use App\Filament\Resources\ActividadComplementarias\ActividadComplementariaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditActividadComplementaria extends EditRecord
{
    protected static string $resource = ActividadComplementariaResource::class;

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

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }
}
