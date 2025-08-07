<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel; // Opcional, para Filament
use Filament\Support\Contracts\HasColor; // Opcional, para Filament
use Filament\Support\Contracts\HasIcon;  // Opcional, para Filament

enum ConductoresEstado: int implements HasLabel, HasColor /*, HasIcon */
{
     case Activo = 1;
    case Inactivo = 2;
    case Vacaciones = 3;
    case Suspendido = 4;
  



    public function getLabel(): ?string
    {
        return match ($this) {
            self::Activo => 'Activo',
            self::Inactivo => 'Inactivo',
            self::Vacaciones => 'Vacaciones',
            self::Suspendido => 'Suspendido',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {

            self::Activo => 'success', // O green
            self::Inactivo => 'warning', // O red
            self::Vacaciones => 'info', // O red
            self::Suspendido => 'danger',
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
