<?php

namespace App\Filament\Resources\ActividadComplementarias\Pages;

use App\Filament\Resources\ActividadComplementarias\ActividadComplementariaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateActividadComplementaria extends CreateRecord
{
    protected static string $resource = ActividadComplementariaResource::class;

    public static function getNavigationLabel(): string
    {
        return 'Registrar '.static::$resource::getModelLabel();
    }

    public function mount(): void
    {
        $user = auth()->user();

        if ($user && $user->actividadComplementaria) {
            $this->redirect(
                ActividadComplementariaResource::getUrl('edit', [
                    'record' => $user->actividadComplementaria->id,
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
