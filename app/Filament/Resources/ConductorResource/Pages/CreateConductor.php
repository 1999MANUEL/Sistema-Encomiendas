<?php

namespace App\Filament\Resources\ConductorResource\Pages;

use App\Filament\Resources\ConductorResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateConductor extends CreateRecord
{
    protected static string $resource = ConductorResource::class;

  
     protected function getCreatedNotification(): ?Notification
    {
        return null;
    }
    protected function afterCreate()
    {
        Notification::make()
            ->title('Conductor Creado')
            ->body('Registro de conductor creado con exito')
            ->success()
            ->send();
    }
}
