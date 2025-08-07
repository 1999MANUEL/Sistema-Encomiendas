<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel; // Opcional, para Filament
use Filament\Support\Contracts\HasColor; // Opcional, para Filament
use Filament\Support\Contracts\HasIcon;  // Opcional, para Filament

enum RolConductorEstado: int implements HasLabel, HasColor /*, HasIcon */
{
    case Principal = 1;
    case Ayudante = 2;
    case Relevo = 3;


    public function getLabel(): ?string
    {
        return match ($this) {
            self::Principal => 'Principal',
            self::Ayudante => 'Ayudante',
            self::Relevo => 'Relevo',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {

            self::Principal => 'success', // O green
            self::Ayudante => 'info', // O red
            self::Relevo => 'warning', // O red
        };
    }

    
}
