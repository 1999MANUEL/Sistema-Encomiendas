<?php

namespace App\Filament\Resources\EncomiendaResource\Pages;

use App\Filament\Resources\EncomiendaResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Pages\Actions\Action;

class EditEncomienda extends EditRecord
{
    protected static string $resource = EncomiendaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return null;
    }
    protected function getSavedNotification(): ?Notification
    {

        return Notification::make()
            ->title('Encomienda Editada')
            ->body('¡La Encomienda se ha actualizado con éxito!')
            ->success();
    }
    
    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->title('Encomienda Eliminada con exito')
                        ->body('La Encomienda ha sido eliminada con exito')
                        ->success()
                ),
                Actions\Action::make('imprimir')
            ->label('Imprimir')
            ->url(fn () => route('encomienda.pdf', ['encomienda' => $this->record->id]))
            ->openUrlInNewTab()
            ->visible(fn () => $this->record !== null),
        ];
    }
}
