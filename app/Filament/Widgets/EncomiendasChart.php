<?php

namespace App\Filament\Widgets;

use App\Models\Encomienda;
use Filament\Widgets\ChartWidget;

class EncomiendasChart extends ChartWidget
{
    protected  static ?string $heading = 'Resumen Mensual de Encomiendas (Bs.)';
protected static ?int $sort = 1;

    protected function getData(): array
    {$data = Encomienda::selectRaw('MONTH(created_at) as mes, SUM(precio) as total')
            ->whereYear('created_at', now()->year)
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total', 'mes');

        $meses = collect(range(1, 12))->map(fn($m) => now()->setMonth($m)->translatedFormat('F'));
        $valores = collect(range(1, 12))->map(fn($m) => $data->get($m, 0));

          return [
            'datasets' => [
                [
                    'label' => 'Bs. por mes',
                    'data' => $valores,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.5)',
                    'borderColor' => 'rgba(34, 197, 94, 1)',
                ],
            ],
            'labels' => $meses,
        ];
    }

    protected function getType(): string
    {
      
        return 'bar';
    }
}
