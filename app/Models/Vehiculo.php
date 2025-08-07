<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Enums\VehiculosEstado; 

class Vehiculo extends Model
{
    use HasFactory;

    protected $table = 'vehiculos'; 
    protected $fillable = [
        'marca',
        'modelo',
        'placa',
        'estado',
    ];

    protected $casts = [
        'estado' => VehiculosEstado::class, 
    ];

   
    public function conductores(): BelongsToMany
    {
        return $this->belongsToMany(Conductor::class, 'conductor_vehiculo', 'vehiculo_id', 'conductor_id')
            ->withPivot('fecha_inicio_asignacion', 'fecha_fin_asignacion', 'rol_conductor')
            ->withTimestamps();
    }

   
    public function getDescripcionCompletaAttribute(): string
    {
        return "{$this->marca} {$this->modelo} ({$this->placa})";
    }
}
