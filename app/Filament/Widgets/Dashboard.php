<?php

namespace App\Filament\Widgets;

use App\Models\Encomienda;
use App\Models\Sucursal;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Dashboard extends BaseWidget
{
    protected function getStats(): array
    {
       return [
        Stat::make("Usuarios",User::count())
        ->description('Total de Uusarios registrados')
        ->icon('heroicon-o-users')
        ->color('success')
        ->chart([1,2,3,4,5,6,7,8,9,10,11,12,13,14,15]),

        Stat::make("Encomiendas",Encomienda::count())
        ->description('Total de Encomiendas registradas')
        ->icon('heroicon-s-squares-plus')
        ->color('warning')
          ->chart([1,1000,2000,3000,4000,5000,6000,7000,8000,9000,10000]),

          Stat::make("Sucursales",Sucursal::count())
        ->description('Total de Sucursales ')
        ->icon('heroicon-s-truck')
        ->color('info')
          ->chart([1,1000,2000,3000,4000,5000,6000,7000,8000,9000,10000])
        ];
        
    }
}
