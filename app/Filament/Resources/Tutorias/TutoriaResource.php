<?php

namespace App\Filament\Resources\Tutorias;

use App\Filament\Resources\Tutorias\RelationManagers\DetallesRelationManager;
use App\Filament\Resources\Tutorias\Pages\CreateTutoria;
use App\Filament\Resources\Tutorias\Pages\EditTutoria;
use App\Filament\Resources\Tutorias\Pages\ListTutorias;
use App\Filament\Resources\Tutorias\Schemas\TutoriaForm;
use App\Filament\Resources\Tutorias\Tables\TutoriasTable;
use App\Models\Tutoria;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class TutoriaResource extends Resource
{
    protected static ?string $model = Tutoria::class;

    protected static ?string $modelLabel = 'Tutoría';

    protected static ?string $pluralModelLabel = 'Tutorías';

    protected static ?string $navigationLabel = 'Tutorías';

    protected static string|null|\UnitEnum $navigationGroup = 'Seguimiento';

    protected static string|null|BackedEnum $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $recordTitleAttribute = 'Tutorias';

    public static function form(Schema $schema): Schema
    {
        return TutoriaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TutoriasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            DetallesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTutorias::route('/'),
            'create' => CreateTutoria::route('/create'),
            'edit' => EditTutoria::route('/{record}/edit'),
        ];
    }
}
