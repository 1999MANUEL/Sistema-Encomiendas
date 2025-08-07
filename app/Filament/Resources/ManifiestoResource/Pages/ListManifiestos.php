<?php

namespace App\Filament\Resources\ManifiestoResource\Pages;

use App\Filament\Resources\ManifiestoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListManifiestos extends ListRecords
{
    protected static string $resource = ManifiestoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
