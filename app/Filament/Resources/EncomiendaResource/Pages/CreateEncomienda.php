<?php

namespace App\Filament\Resources\EncomiendaResource\Pages;

use App\Filament\Resources\EncomiendaResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateEncomienda extends CreateRecord
{
    protected static string $resource = EncomiendaResource::class;
  protected function getRedirectUrl(): string
{
    return $this->getResource()::getUrl('view', ['record' => $this->record]);
}

     protected function getCreatedNotification(): ?Notification
    {
        return null;
    }
    protected function afterCreate()
    {
        Notification::make()
            ->title('Encomienda Creada')
            ->body('Encomienda creada con exito')
            ->success()
            ->send();
    }
}
