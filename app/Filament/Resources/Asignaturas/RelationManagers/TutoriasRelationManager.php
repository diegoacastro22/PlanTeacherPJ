<?php

namespace App\Filament\Resources\Asignaturas\RelationManagers;

use App\Filament\Resources\Tutorias\TutoriaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class TutoriasRelationManager extends RelationManager
{
    protected static string $relationship = 'tutorias';

    protected static ?string $relatedResource = TutoriaResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
