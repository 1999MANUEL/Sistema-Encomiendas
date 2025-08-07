<?php

namespace App\Filament\Widgets;

use App\Enums\EncomiendaEstado;
use App\Models\Encomienda;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;

class TipoEncomiendasChart extends ChartWidget
{
    protected static ?string $heading = '📦 Encomiendas por Estado';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        return Cache::remember('grafico_encomiendas_estado', 60, function () {
            $data = Encomienda::selectRaw('estado, COUNT(*) as total')
                ->groupBy('estado')
                ->pluck('total', 'estado');

            $labels = $data->keys()->map(function ($estadoValor) {
                if (EncomiendaEstado::tryFrom((int) $estadoValor)) {
                    return EncomiendaEstado::from((int) $estadoValor)->getLabel();
                }
                return 'Desconocido (' . $estadoValor . ')';
            });

            return [
                'datasets' => [
                    [
                        'label' => 'Cantidad',
                        'data' => $data->values(),
                        'backgroundColor' => [
                            '#3b82f6',
                            '#10b981',
                            '#f59e0b',
                            '#ef4444',
                            '#8b5cf6',
                        ],
                    ],
                ],
                'labels' => $labels,
            ];
        });
    }

    protected function getType(): string
    {
        return 'pie';
    }
}