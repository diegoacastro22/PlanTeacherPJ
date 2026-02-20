<?php

namespace App\Filament\Resources\Informes\Pages;

use App\Filament\Resources\Informes\InformeResource;
use App\Models\Informe;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditInforme extends EditRecord
{
    protected static string $resource = InformeResource::class;

    protected function getHeaderActions(): array
    {
        $informe = $this->record instanceof Informe ? $this->record : null;
        return [
            Action::make('ver_pdf')
                ->label('Ver PDF en nueva pestaña')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->color('info')
                ->url(fn (): string =>
                $informe->hasMedia('informe_pdf')
                    ? $informe->getFirstMediaUrl('informe_pdf')
                    : '#'
                )
                ->openUrlInNewTab()
                ->visible(fn (): bool => $informe->hasMedia('informe_pdf')),

            Action::make('descargar_pdf')
                ->label('Descargar PDF')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->url(fn (): ?string =>
                $informe->hasMedia('informe_pdf')
                    ? $informe->getFirstMedia('informe_pdf')->getUrl()
                    : null
                )
                ->extraAttributes(fn (): array =>
                $informe->hasMedia('informe_pdf')
                    ? ['download' => $informe->getFirstMedia('informe_pdf')->file_name]
                    : []
                )
                ->visible(fn (): bool => $informe->hasMedia('informe_pdf')),

            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
