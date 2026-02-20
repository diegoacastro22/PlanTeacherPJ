<?php

namespace App\Filament\Resources\ActividadComplementarias\Pages;

use App\Filament\Resources\ActividadComplementarias\ActividadComplementariaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListActividadComplementarias extends ListRecords
{
    protected static string $resource = ActividadComplementariaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'Listado de '.static::$resource::getPluralModelLabel();
    }
}
