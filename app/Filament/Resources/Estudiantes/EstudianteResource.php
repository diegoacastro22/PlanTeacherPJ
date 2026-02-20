<?php

namespace App\Filament\Resources\Estudiantes;

use App\Filament\Resources\Estudiantes\Pages\CreateEstudiante;
use App\Filament\Resources\Estudiantes\Pages\EditEstudiante;
use App\Filament\Resources\Estudiantes\Pages\ListEstudiantes;
use App\Filament\Resources\Estudiantes\Schemas\EstudianteForm;
use App\Filament\Resources\Estudiantes\Tables\EstudiantesTable;
use App\Models\Estudiante;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class EstudianteResource extends Resource
{
    protected static ?string $model = Estudiante::class;

    protected static ?string $modelLabel = 'Estudiante';

    protected static ?string $pluralModelLabel = 'Estudiantes';

    protected static ?string $navigationLabel = 'Estudiantes';

    protected static string|null|\UnitEnum $navigationGroup = 'Académico';

    protected static string|null|BackedEnum $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $recordTitleAttribute = 'Estudiantes';

    public static function form(Schema $schema): Schema
    {
        return EstudianteForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EstudiantesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEstudiantes::route('/'),
            'create' => CreateEstudiante::route('/create'),
            'edit' => EditEstudiante::route('/{record}/edit'),
        ];
    }
}
