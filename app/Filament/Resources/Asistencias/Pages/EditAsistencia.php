<?php

namespace App\Filament\Resources\Asistencias\Pages;

use App\Filament\Resources\Asistencias\AsistenciaResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EditAsistencia extends EditRecord
{
    protected static string $resource = AsistenciaResource::class;

    protected $listeners = ['$refresh' => '$refresh'];

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    // Opcional: Refrescar el formulario completo
    public function refreshForm(): void
    {
        $this->form->fill();
    }

    public function getTitle(): string
    {
        return 'Editar Asistencia';
    }

    public static function shouldRegisterSpotlight(): bool
    {
        return false;
    }

    protected function getSaveFormAction(): Action
    {
        return parent::getSaveFormAction()
            ->disabled(fn () => $this->record->finalizada);
    }

    protected function beforeSave(): void
    {
        $data = $this->form->getState();
        $fecha = $data['fecha'] ?? null;
        $asignatura = $this->record->asignatura_id ?? null;
        if ($fecha && $asignatura) {
            $exists = static::$resource::getModel()::query()
                ->whereDate('fecha', $fecha)
                ->where('asignatura_id', $asignatura)
                ->where('id', '!=', $this->record->id)
                ->exists();

            if ($exists) {
                Notification::make()
                    ->title('Ya existe una asistencia con esta fecha y esta asignatura.')
                    ->danger()
                    ->send();

                $this->halt();
            }
        }
    }

}
