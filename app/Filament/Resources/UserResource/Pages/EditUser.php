<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;
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
            ->title('Usuario Editado')
            ->body('¡El registro se ha actualizado con éxito!')
            ->success();
    }


    protected function getHeaderActions(): array
    {
        return [
           Actions\DeleteAction::make()
            ->successNotification(
                    Notification::make()
                        ->title('Usuario Eliminado con exito')
                        ->body('El registro se ha sido eliminado con exito')
                        ->success()
                ),
        ];
    }
}
