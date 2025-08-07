<?php

namespace App\Models;

use App\Enums\EncomiendaEstado;
use App\Enums\ManifiestoEstado;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Manifiesto extends Model
{
    protected $table = 'manifiestos';

    protected $fillable = [
         'codigo_manifiesto',
        'conductor_id',
        'vehiculo_id',
        'fecha_envio',
        'hora_envio',
        'estado',
        'precio_total',
        'sucursal_origen_id',
        'sucursal_destino_id',
  
    ];
     protected $casts = [
        'estado'=>ManifiestoEstado::class,
     ];

    // Relación con Conductor
    public function conductor(): BelongsTo
    {
        return $this->belongsTo(Conductor::class);
    }

    // Relación con Vehículo
    public function vehiculo(): BelongsTo
    {
        return $this->belongsTo(Vehiculo::class);
    }

    // Relación con Sucursal Origen
    public function sucursalOrigen(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_origen_id');
    }

    // Relación con Sucursal Destino
    public function sucursalDestino(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_destino_id');
    }

 public function encomiendas()
    {
        return $this->belongsToMany(
            Encomienda::class,
            'encomienda_manifiesto', // nombre tabla pivote
            'manifiesto_id',
            'encomienda_id'
        );
    }

    // Método para sincronizar encomiendas y actualizar precio total
    public function syncEncomiendas(array $ids)
    {
        $this->encomiendas()->sync($ids);

        $this->precio_total = $this->encomiendas()->sum('precio');
        $this->save();
    }

    // Accesor para obtener precio total (opcional)
    public function getPrecioTotalAttribute()
    {
        return $this->encomiendas()->sum('precio');
    }
protected static function boot(){
    parent::boot();
    static::creating(function ($manifiesto){
        $maxNumero=self::query()
        ->selectRaw('MAX(CAST(SUBSTRING(codigo_manifiesto,5)AS UNSIGNED)) as max_num')
        ->where('codigo_manifiesto','LIKE','G-%')
        ->first()
        ->max_num;
        $nuevoNumero = ($maxNumero?? 0)+1;
        $manifiesto->codigo_manifiesto='M-'.str_pad($nuevoNumero,8,'0',STR_PAD_LEFT);
    });
}
}
