<?php

namespace App\Filament\Resources\Estudiantes\Pages;

use App\Filament\Resources\Estudiantes\EstudianteResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEstudiante extends CreateRecord
{
    protected static string $resource = EstudianteResource::class;

    public function getTitle(): string
    {
        return 'Registrar Estudiante';
    }

    public static function getNavigationLabel(): string
    {
        return 'Registrar '.static::$resource::getModelLabel();
    }
}
