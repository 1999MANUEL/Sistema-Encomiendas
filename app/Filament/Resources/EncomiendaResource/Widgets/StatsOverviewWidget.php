<?php

namespace App\Filament\Resources\EncomiendaResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Encomienda; // Importa tu modelo Encomienda
use App\Enums\EncomiendaEstado; // Importa tu Enum de estados

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalEncomiendas = Encomienda::count();
        $encomiendasRegistradas = Encomienda::where('estado', EncomiendaEstado::Registrada)->count();
        $encomiendasEntregadas = Encomienda::where('estado', EncomiendaEstado::Entregada)->count();

        return [
            Stat::make('Total de Encomiendas', $totalEncomiendas)
                ->description('Todas las encomiendas registradas')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Encomiendas Registradas', $encomiendasRegistradas)
                ->description('Encomiendas actualmente en camino o en  almacen')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('warning'),
             Stat::make('Encomiendas Entregadas', $encomiendasEntregadas)
                ->description('Encomiendas actualmente entregadas')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('info'),
        ];
    }
}