<?php

namespace App\Filament\Resources\EncomiendaResource\Pages;

use App\Filament\Resources\EncomiendaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\EncomiendaResource\Widgets\StatsOverviewWidget;
use Filament\Forms\Components\FileUpload;

class ListEncomiendas extends ListRecords
{
    protected static string $resource = EncomiendaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
             
        ];
    }
     
    protected function getHeaderWidgets(): array
    {
        return [
            StatsOverviewWidget::class,
        ];
    }
}
