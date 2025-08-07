<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

enum EncomiendaEstado: int implements HasLabel, HasColor
{
    case Registrada = 1;
    case Entregada = 2;
 

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Registrada => 'Registrada',
           self::Entregada => 'Entregada',
     
     
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Registrada => 'info',     
            self::Entregada => 'success',
    
        };
    }
}