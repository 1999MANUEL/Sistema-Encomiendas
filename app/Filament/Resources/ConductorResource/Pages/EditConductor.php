<?php

namespace App\Filament\Resources\ConductorResource\Pages;

use App\Filament\Resources\ConductorResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditConductor extends EditRecord
{
    protected static string $resource = ConductorResource::class;

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
            ->title('Conductor Editado')
            ->body('¡El registro se ha actualizado con éxito!')
            ->success();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->successNotification(
                    Notification::make()
                        ->title('Conductor Eliminado con exito')
                        ->body('El registro se ha sido eliminado con exito')
                        ->success()
                ),
        ];
    }
}
