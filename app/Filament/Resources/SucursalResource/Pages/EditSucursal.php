<?php

namespace App\Filament\Resources\SucursalResource\Pages;

use App\Filament\Resources\SucursalResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditSucursal extends EditRecord
{
    protected static string $resource = SucursalResource::class;

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
            ->title('Oficina Editada')
            ->body('¡La Oficina se ha actualizado con éxito!')
            ->success();

        
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->title('Oficina Eliminada')
                        ->body('Oficina eliminada con exito')
                        ->success()
                ),
        ];
    }
}
