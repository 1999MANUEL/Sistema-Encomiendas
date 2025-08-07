<?php

namespace App\Models;

use App\Enums\ConductoresEstado;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
// Asumiendo que crearás este Enum similar a VehiculosEstado

class Conductor extends Model
{
    use HasFactory;

    protected $table = 'conductores'; 

    protected $fillable = [
        'nombre_conductor',
        'celular_conductor',
        'conductor_num_licencia',
        'fecha_exp_licencia',
        'fecha_contratacion',
        'estado',
    ];

    protected $casts = [
        'fecha_exp_licencia' => 'date',
        'fecha_contratacion' => 'date',
        'estado' => ConductoresEstado::class,
    ];

    
    public function vehiculos(): BelongsToMany
    {
        return $this->belongsToMany(Vehiculo::class, 'conductor_vehiculo', 'conductor_id', 'vehiculo_id')
            ->withPivot('fecha_inicio_asignacion', 'fecha_fin_asignacion', 'rol_conductor')
            ->withTimestamps(); 
    }


    public function getNombreCompletoAttribute(): string
    {
        return $this->nombre_conductor;
    }
}
