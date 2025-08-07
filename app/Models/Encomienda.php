<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\EncomiendaEstado; // Importa tu Enum para el estado de la Encomienda
// use App\Enums\EncomiendaTipo; // Si también creaste un Enum para el tipo, impórtalo aquí

class Encomienda extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_guia',
        'nombre_remitente',
        'direccion_remitente',
        'contacto_remitente',
        'nombre_destinatario',
        'direccion_destinatario',
        'contacto_destinatario',
        'descripcion_contenido',
        'cantidad_paquetes',
        'valor_declarado',
        'tiene_valor_declarado', // Nuevo campo boolean
        // 'tipo', // Si decides usar 'tipo' más adelante, actívalo aquí
        'sucursal_origen_id',
        'sucursal_destino_id',
        'peso',
        'altura',
        'ancho',
        'largo',
        'precio', // Asumimos que es precio total
        'estado',
        'monto_pagado',
        'saldo_pendiente',
        'estado_pago'
    ];

    protected $casts = [
        'tiene_valor_declarado' => 'boolean',
        'valor_declarado' => 'decimal:2',
        'peso' => 'decimal:2',
        'altura' => 'decimal:2',
        'ancho' => 'decimal:2',
        'largo' => 'decimal:2',
        'precio' => 'decimal:2',
        'estado' => EncomiendaEstado::class, // ¡IMPORTANTE! Castear a tu Enum
        // 'tipo' => EncomiendaTipo::class, // Si creaste un Enum para 'tipo', castealo aquí también
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relación con sucursal de origen
    public function sucursalOrigen(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_origen_id');
    }

    // Relación con sucursal de destino
    public function sucursalDestino(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_destino_id');
    }

    // Relación con los seguimientos
    public function seguimientos(): HasMany
    {
        return $this->hasMany(Seguimiento::class, 'encomienda_id');
    }
    public function paquetes()
    {
        return $this->hasMany(Paquete::class);
    }
public function manifiestos()
{
    return $this->belongsToMany(Manifiesto::class, 'encomienda_manifiesto', 'encomienda_id', 'manifiesto_id');
}

protected static function boot(){
    parent::boot();
    static::creating(function ($encomienda){
        $maxNumero=self::query()
        ->selectRaw('MAX(CAST(SUBSTRING(numero_guia,5)AS UNSIGNED)) as max_num')
        ->where('numero_guia','LIKE','G-%')
        ->first()
        ->max_num;
        $nuevoNumero = ($maxNumero?? 0)+1;
        $encomienda->numero_guia='G-'.str_pad($nuevoNumero,8,'0',STR_PAD_LEFT);
    });
}
}
