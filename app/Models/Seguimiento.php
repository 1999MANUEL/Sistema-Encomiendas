<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\SeguimientoEstado; // Importa tu Enum para el estado del Seguimiento

class Seguimiento extends Model
{
    use HasFactory;

    protected $fillable = [
        'encomienda_id',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'estado' => SeguimientoEstado::class, // ¡IMPORTANTE! Castear a tu Enum
        'created_at' => 'datetime', // Añadir si quieres castear las timestamps
        'updated_at' => 'datetime', // Añadir si quieres castear las timestamps
    ];

    public function encomienda(): BelongsTo
    {
        return $this->belongsTo(Encomienda::class);
    }

    // Relación con el usuario que registró el seguimiento (si lo necesitas)
    // Asumo que 'usuario_id' es el nombre de la FK en la tabla 'seguimientos'
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // ELIMINAR O REEMPLAZAR:
    // Este método `descripcionEstado()` ya no es necesario aquí.
    // La lógica de la descripción del estado ahora vive en el Enum `SeguimientoEstado`.
    // Puedes acceder a la etiqueta así: `$seguimiento->estado->getLabel()`
    /*
    public function descripcionEstado(): string
    {
        $estados = [
            1 => 'Registrado en sistema',
            2 => 'Recibido en sucursal origen',
            3 => 'En transporte a centro de distribución',
            4 => 'En centro de distribución',
            5 => 'En transporte a sucursal destino',
            6 => 'En sucursal destino',
            7 => 'En reparto',
            8 => 'Entregado',
            9 => 'Incidente reportado',
        ];

        return $estados[$this->estado] ?? 'Estado desconocido';
    }
    */
}