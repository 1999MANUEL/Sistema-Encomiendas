<?php

namespace App\Filament\Resources\SucursalResource\Pages;

use App\Filament\Resources\SucursalResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSucursal extends ViewRecord
{
    protected static string $resource = SucursalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
