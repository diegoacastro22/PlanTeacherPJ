<?php

namespace App\Filament\Resources\PlanTrabajos;

use App\Models\PlanTrabajo;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;

class PlanTrabajoResource extends Resource
{
    protected static ?string $model = PlanTrabajo::class;

    protected static ?string $modelLabel = 'Plan de Trabajo';

    protected static ?string $pluralModelLabel = 'Plan de Trabajo';

    protected static ?string $navigationLabel = 'Mi Plan de Trabajo';

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-document-text';

    protected static string|null|\UnitEnum $navigationGroup = 'Gestión Académica';

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->components([
                Section::make('Información del Plan de Trabajo')
                    ->schema([
                        Forms\Components\TextInput::make('titulo')
                            ->label('Título')
                            ->maxLength(255)
                            ->placeholder('Ej: Plan de Trabajo Semestre 2025-1'),

                        Forms\Components\Textarea::make('descripcion')
                            ->label('Descripción')
                            ->rows(4)
                            ->placeholder('Descripción opcional del plan de trabajo...'),
                    ]),

                Section::make('Documento PDF')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('plan_trabajo')
                            ->label('Subir Plan de Trabajo (PDF)')
                            ->collection('plan_trabajo')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(10240) // 10MB máximo
                            ->downloadable()
                            ->openable()
                            ->required()
                            ->helperText('Sube tu plan de trabajo en formato PDF (máximo 10MB)'),
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ViewPlanTrabajo::route('/'),
        ];
    }

    // Solo permitir crear si el usuario no tiene ya un plan de trabajo
    public static function canCreate(): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        return ! $user->planTrabajo()->exists();
    }
}
