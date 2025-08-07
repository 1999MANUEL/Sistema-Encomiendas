<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sucursal extends Model
{
    use HasFactory;
    protected $table ='sucursales';

    protected $fillable = [
        'codigo_sucursal',
        'calle',
        'zona',
        'ciudad',
        'contacto',
    ];

    // Encomiendas que salen de esta sucursal
    public function encomiendasOrigen(): HasMany
    {
        return $this->hasMany(Encomienda::class, 'sucursal_origen_id');
    }

    // Encomiendas que llegan a esta sucursal
    public function encomiendasDestino(): HasMany
    {
        return $this->hasMany(Encomienda::class, 'sucursal_destino_id');
    }

    // Usuarios asignados a esta sucursal
    public function usuarios(): HasMany
    {
        return $this->hasMany(User::class);
    }
}