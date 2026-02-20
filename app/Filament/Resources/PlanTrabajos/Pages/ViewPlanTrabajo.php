<?php

namespace App\Filament\Resources\PlanTrabajos\Pages;

use App\Filament\Resources\PlanTrabajos\PlanTrabajoResource;
use App\Models\PlanTrabajo;
use Filament\Actions\Action;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;

class ViewPlanTrabajo extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = PlanTrabajoResource::class;

    protected string $view = 'filament.resources.plan-trabajos.pages.view-plan-trabajo';

    public ?PlanTrabajo $planTrabajo = null;

    public ?array $data = [];

    public function mount(): void
    {
        $this->planTrabajo = auth()->user()->planTrabajo;

        if ($this->planTrabajo) {
            $this->form->fill([
                'titulo' => $this->planTrabajo->titulo,
                'descripcion' => $this->planTrabajo->descripcion,
            ]);
        }
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('titulo')
                ->label('Título')
                ->required()
                ->maxLength(255),

            Textarea::make('descripcion')
                ->label('Descripción')
                ->rows(4)
                ->columnSpanFull(),

            SpatieMediaLibraryFileUpload::make('pdf')
                ->label('Documento PDF')
                ->collection('plan_trabajo')
                ->acceptedFileTypes(['application/pdf'])
                ->maxSize(10240)
                ->downloadable()
                ->openable()
                ->columnSpanFull()
                ->helperText('Sube un PDF del plan de trabajo (máx. 10MB)'),
        ];
    }

    protected function getFormStatePath(): string
    {
        return 'data';
    }

    protected function getFormModel(): ?PlanTrabajo
    {
        return $this->planTrabajo;
    }

    public function getTitle(): string
    {
        return 'Ver Plan de Trabajo';
    }

    // Modal de eliminación usando Actions
    public function deleteAction(): Action
    {
        return Action::make('delete')
            ->label('Eliminar Plan')
            ->color('danger')
            ->icon('heroicon-o-trash')
            ->requiresConfirmation()
            ->modalHeading('Eliminar Plan de Trabajo')
            ->modalDescription('¿Estás seguro de que deseas eliminar tu plan de trabajo? Esta acción no se puede deshacer y se eliminará el documento PDF asociado.')
            ->modalSubmitActionLabel('Sí, eliminar')
            ->modalCancelActionLabel('Cancelar')
            ->action(function () {
                if ($this->planTrabajo) {
                    $this->planTrabajo->clearMediaCollection('plan_trabajo');
                    $this->planTrabajo->delete();

                    $this->planTrabajo = null;

                    Notification::make()
                        ->success()
                        ->title('Plan eliminado correctamente')
                        ->body('El plan de trabajo y su documento han sido eliminados.')
                        ->send();

                    $this->mount();
                }
            });
    }

    public function save(): void
    {
        $data = $this->form->getState();

        if ($this->planTrabajo) {
            // Actualizar plan existente
            $this->planTrabajo->update([
                'titulo' => $data['titulo'],
                'descripcion' => $data['descripcion'],
            ]);

            Notification::make()
                ->success()
                ->title('Plan actualizado correctamente')
                ->send();
        } else {
            // Crear nuevo plan
            $this->planTrabajo = PlanTrabajo::create([
                'titulo' => $data['titulo'],
                'descripcion' => $data['descripcion'],
                'user_id' => auth()->id(),
            ]);

            // Re-vincular el formulario al nuevo modelo y guardar relaciones
            $this->form->model($this->planTrabajo)->saveRelationships();

            Notification::make()
                ->success()
                ->title('Plan creado correctamente')
                ->send();
        }

        // Refrescar el formulario
        $this->mount();
    }
}
