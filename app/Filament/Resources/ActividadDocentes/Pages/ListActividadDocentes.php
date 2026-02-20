<?php

namespace App\Filament\Resources\ActividadDocentes\Pages;

use App\Filament\Resources\ActividadDocentes\ActividadDocenteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListActividadDocentes extends ListRecords
{
    protected static string $resource = ActividadDocenteResource::class;

    public function getTitle(): string
    {
        return 'Listado de Actividades';
    }

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
