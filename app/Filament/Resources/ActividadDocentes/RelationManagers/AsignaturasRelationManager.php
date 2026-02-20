<?php

namespace App\Filament\Resources\ActividadDocentes\RelationManagers;

use App\Filament\Resources\Asignaturas\AsignaturaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class AsignaturasRelationManager extends RelationManager
{
    protected static string $relationship = 'asignaturas';

    protected static ?string $relatedResource = AsignaturaResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
