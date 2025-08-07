<?php

namespace App\Filament\Resources\VehiculoResource\Pages;

use App\Filament\Resources\VehiculoResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateVehiculo extends CreateRecord
{
    protected static string $resource = VehiculoResource::class;
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
        ->title('Vehiculo Creada')
        ->body('Vehiculo creado con exito')
        ->success()
        ->send();
    }
}
