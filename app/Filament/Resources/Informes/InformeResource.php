<?php

namespace App\Filament\Resources\Informes;

use App\Filament\Resources\Informes\Pages;
use App\Models\Informe;
use App\Models\Estudiante;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Illuminate\Support\Facades\Auth;

class InformeResource extends Resource
{
    protected static ?string $model = Informe::class;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-document-text';

    protected static string|null|\UnitEnum $navigationGroup = 'Gestión Académica';
    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Informes';

    protected static ?string $modelLabel = 'Informe';

    protected static ?string $pluralModelLabel = 'Informes';

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->components([
                Section::make('Información del Informe')
                    ->schema([
                        Forms\Components\Hidden::make('user_id')
                            ->label('Usuario'),

                        Forms\Components\Select::make('estudiante_id')
                            ->label('Estudiante')
                            ->relationship('estudiante', 'nombre_completo')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\Select::make('tipo_actividad')
                            ->label('Tipo de Actividad')
                            ->options([
                                'orientacion_evaluacion_trabajos_grado' => 'Orientación y evaluación de trabajos de grado',
                                'investigacion_aprobada' => 'Investigación aprobada',
                                'proyeccion_social_registrada' => 'Proyección social registrada',
                                'cooperacion_interinstitucional' => 'Cooperación interinstitucional',
                                'crecimiento_personal_profesional' => 'Crecimiento personal y profesional',
                                'otras_actividades' => 'Otras actividades',
                            ])
                            ->required()
                            ->searchable()
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('observaciones')
                            ->label('Observaciones')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Documento PDF')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('informe_pdf')
                            ->label('Archivo PDF')
                            ->collection('informe_pdf')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(10240) // 10MB
                            ->downloadable()
                            ->openable()
                            ->required()
                            ->helperText('Sube el documento PDF del informe (máximo 10MB)')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Usuario')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('estudiante.nombre_completo')
                    ->label('Estudiante')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('estudiante.codigo')
                    ->label('Código')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tipo_actividad')
                    ->label('Tipo de Actividad')
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'orientacion_evaluacion_trabajos_grado' => 'Orientación y evaluación de trabajos de grado',
                        'investigacion_aprobada' => 'Investigación aprobada',
                        'proyeccion_social_registrada' => 'Proyección social registrada',
                        'cooperacion_interinstitucional' => 'Cooperación interinstitucional',
                        'crecimiento_personal_profesional' => 'Crecimiento personal y profesional',
                        'actividades_administrativas' => 'Actividades administrativas',
                        'otras_actividades' => 'Otras actividades',
                        'compartidas_horas_semanales' => 'Compartidas con las horas semanales',
                        default => $state,
                    })
                    ->wrap()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('tiene_pdf')
                    ->label('PDF')
                    ->boolean()
                    ->getStateUsing(fn (Informe $record): bool => $record->hasMedia('informe_pdf'))
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Creación')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Última Actualización')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipo_actividad')
                    ->label('Tipo de Actividad')
                    ->options([
                        'orientacion_evaluacion_trabajos_grado' => 'Orientación y evaluación de trabajos de grado',
                        'investigacion_aprobada' => 'Investigación aprobada',
                        'proyeccion_social_registrada' => 'Proyección social registrada',
                        'cooperacion_interinstitucional' => 'Cooperación interinstitucional',
                        'crecimiento_personal_profesional' => 'Crecimiento personal y profesional',
                        'actividades_administrativas' => 'Actividades administrativas',
                        'otras_actividades' => 'Otras actividades',
                        'compartidas_horas_semanales' => 'Compartidas con las horas semanales',
                    ]),

                Tables\Filters\SelectFilter::make('estudiante')
                    ->relationship('estudiante', 'nombre_completo')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                Action::make('ver_pdf')
                    ->label('Ver PDF')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn (Informe $record): string =>
                    $record->hasMedia('informe_pdf')
                        ? $record->getFirstMediaUrl('informe_pdf')
                        : '#'
                    )
                    ->openUrlInNewTab()
                    ->visible(fn (Informe $record): bool => $record->hasMedia('informe_pdf')),

                Action::make('descargar_pdf')
                    ->label('Descargar')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->url(fn (Informe $record): ?string =>
                    $record->hasMedia('informe_pdf')
                        ? $record->getFirstMedia('informe_pdf')->getUrl()
                        : null
                    )
                    ->extraAttributes(fn (Informe $record): array =>
                    $record->hasMedia('informe_pdf')
                        ? ['download' => $record->getFirstMedia('informe_pdf')->file_name]
                        : []
                    )
                    ->visible(fn (Informe $record): bool => $record->hasMedia('informe_pdf')),

                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    \Filament\Actions\ExportBulkAction::make('exportar_seleccionados')
                        ->label('Exportar seleccionados')
                        ->exporter(\App\Filament\Exports\InformeExporter::class)
                        ->filename('informes_seleccionados'),
                ]),

                ExportAction::make('exportar_todo')
                    ->label('Exportar todo')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->exporter(\App\Filament\Exports\InformeExporter::class)
                    ->fileName('informes_completos_' . now()->format('Y-m-d_His'))
                    ->modifyQueryUsing(function ($query) {
                        // Solo informes del docente actual
                        return $query->where('user_id', auth()->id());
                    }),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListInformes::route('/'),
            'create' => Pages\CreateInforme::route('/create'),
            'edit' => Pages\EditInforme::route('/{record}/edit'),
        ];
    }
}
