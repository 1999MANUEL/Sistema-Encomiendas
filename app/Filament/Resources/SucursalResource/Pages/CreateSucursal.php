<?php

namespace App\Filament\Resources\SucursalResource\Pages;

use App\Filament\Resources\SucursalResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateSucursal extends CreateRecord
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
    protected function afterCreate(){
        Notification::make()
        ->title('Oficina Creada')
        ->body('Oficina creada con exito')
        ->success()
        ->send();
    }
}
