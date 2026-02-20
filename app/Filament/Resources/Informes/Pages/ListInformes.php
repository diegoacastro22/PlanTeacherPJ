<?php

namespace App\Filament\Resources\Informes\Pages;

use App\Filament\Resources\Informes\InformeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInformes extends ListRecords
{
    protected static string $resource = InformeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
