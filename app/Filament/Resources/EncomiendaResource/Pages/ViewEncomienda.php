<?php

namespace App\Filament\Resources\EncomiendaResource\Pages;

use App\Filament\Resources\EncomiendaResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewEncomienda extends ViewRecord
{
    protected static string $resource = EncomiendaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
             Actions\Action::make('imprimir')
            ->label('Imprimir')
            ->url(fn () => route('encomienda.pdf', ['encomienda' => $this->record->id]))
            ->openUrlInNewTab()
            ->color('warning')
            ->visible(fn () => $this->record !== null),
        ];
    }
}
