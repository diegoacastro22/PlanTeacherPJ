<?php

namespace App\Filament\Resources\ActividadComplementarias;

use App\Filament\Resources\ActividadComplementarias\Pages\CreateActividadComplementaria;
use App\Filament\Resources\ActividadComplementarias\Pages\EditActividadComplementaria;
use App\Filament\Resources\ActividadComplementarias\Schemas\ActividadComplementariaForm;
use App\Filament\Resources\ActividadComplementarias\Tables\ActividadComplementariasTable;
use App\Models\ActividadComplementaria;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ActividadComplementariaResource extends Resource
{
    protected function mount(): void
    {
        parent::mount();

        $user = auth()->user();
        if ($user && $user->actividadComplementaria()->exists()) {
            $record = $user->actividadComplementaria()->first();
            // Redirige a la página de edición del registro existente
            redirect(ActividadComplementariaResource::getUrl('edit', ['record' => $record->getKey()]));
        }
    }

    protected static ?string $model = ActividadComplementaria::class;

    protected static ?string $modelLabel = 'Actividad Complementaria';

    protected static ?string $pluralModelLabel = 'Actividades Complementarias';

    protected static ?string $navigationLabel = 'Actividades Complementarias';

    protected static ?int $navigationSort = 2; // <-- orden en la navegación

    protected static string|null|\UnitEnum $navigationGroup = 'Gestión Académica';

    protected static string|null|BackedEnum $navigationIcon = 'heroicon-o-queue-list';

    protected static ?string $recordTitleAttribute = 'Actividades Complementarias';

    public static function form(Schema $schema): Schema
    {
        return ActividadComplementariaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ActividadComplementariasTable::configure($table);
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
            'index' => CreateActividadComplementaria::route('/'),
            'create' => CreateActividadComplementaria::route('/create'),
            'edit' => EditActividadComplementaria::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        $user = auth()->user();
        if (! $user) {
            return false;
        }

        // Solo permitir crear si el usuario NO tiene ya una actividad docente
        return ! (bool) $user->actividadComplementaria()->exists();
    }
}
