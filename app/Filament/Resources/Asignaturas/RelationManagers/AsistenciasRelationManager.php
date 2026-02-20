<?php

namespace App\Filament\Resources\Asignaturas\RelationManagers;

use App\Filament\Resources\Asistencias\AsistenciaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class AsistenciasRelationManager extends RelationManager
{
    protected static string $relationship = 'asistencias';

    protected static ?string $relatedResource = AsistenciaResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
