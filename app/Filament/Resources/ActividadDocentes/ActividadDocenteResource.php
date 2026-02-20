<?php

namespace App\Filament\Resources\ActividadDocentes;

use App\Filament\Resources\ActividadDocentes\Pages\CreateActividadDocente;
use App\Filament\Resources\ActividadDocentes\Pages\EditActividadDocente;
use App\Filament\Resources\ActividadDocentes\Schemas\ActividadDocenteForm;
use App\Filament\Resources\ActividadDocentes\Tables\ActividadDocentesTable;
use App\Models\ActividadDocente;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ActividadDocenteResource extends Resource
{
    protected static ?string $model = ActividadDocente::class;

    protected static ?string $modelLabel = 'Actividad del Docente';

    protected static ?string $pluralModelLabel = 'Actividades del Docente';

    protected static ?string $navigationLabel = 'Mis Actividades';

    protected static ?int $navigationSort = 1;

    protected static string|null|\UnitEnum $navigationGroup = 'Gestión Académica';

    protected static string|null|BackedEnum $navigationIcon = 'heroicon-o-clipboard-document';

    protected static ?string $recordTitleAttribute = 'Actividad Principal';

    public static function form(Schema $schema): Schema
    {
        return ActividadDocenteForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ActividadDocentesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\AsignaturasRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => CreateActividadDocente::route('/'),
            'create' => CreateActividadDocente::route('/create'),
            'edit' => EditActividadDocente::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        $user = auth()->user();
        if (! $user) {
            return false;
        }

        // Solo permitir crear si el usuario NO tiene ya una actividad docente
        return ! (bool) $user->actividadDocente()->exists();
    }
}
