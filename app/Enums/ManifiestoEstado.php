<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;


enum ManifiestoEstado: int implements HasLabel, HasColor //, HasIcon
{
    case Creado = 1;

    case Entregado = 2;

    case EnEspera = 3; 

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Creado => 'Creado',
           self::Entregado => 'Entregado',
            self::EnEspera => 'En Espera',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Creado => 'info',
            self::Entregado => 'success',       
            self::EnEspera => 'warning',  
        };
    }

    
}