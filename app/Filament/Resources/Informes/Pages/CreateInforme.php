<?php

namespace App\Filament\Resources\Informes\Pages;

use App\Filament\Resources\Informes\InformeResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateInforme extends CreateRecord
{
    protected static string $resource = InformeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Asignar automáticamente el user_id del usuario autenticado
        $data['user_id'] = Auth::id();

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
