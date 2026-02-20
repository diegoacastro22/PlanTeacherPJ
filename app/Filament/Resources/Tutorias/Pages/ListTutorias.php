<?php

namespace App\Filament\Resources\Tutorias\Pages;

use App\Filament\Resources\Tutorias\TutoriaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTutorias extends ListRecords
{
    protected static string $resource = TutoriaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Tutorías';
    }

    public static function getNavigationLabel(): string
    {
        return 'Listado de '.static::$resource::getPluralModelLabel();
    }
}
