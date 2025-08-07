<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel; // Opcional, para Filament
use Filament\Support\Contracts\HasColor; // Opcional, para Filament
use Filament\Support\Contracts\HasIcon;  // Opcional, para Filament

enum SeguimientoEstado: int implements HasLabel, HasColor /*, HasIcon */
{
    case Registrado = 1;
    case EnTransito = 2;
    case EnDistribucion = 3;
    case Entregado = 4;
    case Fallida = 5;
    case Cancelado = 6;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Registrado => 'Registrado',
            self::EnTransito => 'En Tránsito',
            self::EnDistribucion => 'En Distribución',
            self::Entregado => 'Entregado',
            self::Fallida => 'Entrega Fallida',
            self::Cancelado => 'Cancelado',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Registrado => 'gray',
            self::EnTransito => 'info', // O blue
            self::EnDistribucion => 'warning', // O yellow
            self::Entregado => 'success', // O green
            self::Fallida => 'danger', // O red
            self::Cancelado => 'danger', // O red
        };
    }

   
}