<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel; // Opcional, para Filament
use Filament\Support\Contracts\HasColor; // Opcional, para Filament
use Filament\Support\Contracts\HasIcon;  // Opcional, para Filament

enum VehiculosEstado: int implements HasLabel, HasColor /*, HasIcon */
{

    case Disponible = 1;
    case EnMantenimiento = 2;
    case FueraDeServicio = 3;


    public function getLabel(): ?string
    {
        return match ($this) {
            self::Disponible => 'Disponible',
            self::EnMantenimiento => 'En Mantenimiento',
            self::FueraDeServicio => 'Fuera De Servicio',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {

            self::Disponible => 'success', // O green
            self::EnMantenimiento => 'info', // O red
            self::FueraDeServicio => 'danger', // O red

        };
    }

    /*
    public function getIcon(): ?string
    {
        return match ($this) {
            self::Registrado => 'heroicon-m-clipboard-document-list',
            // ...
        };
    }
    */
}
