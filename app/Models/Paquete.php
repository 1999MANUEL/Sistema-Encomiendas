<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paquete extends Model
{
    use HasFactory;

    protected $fillable = [
        'encomienda_id',
        'descripcion',
        'peso',
        'altura',
        'ancho',
        'largo',
        'precio',
    ];

    public function encomienda()
    {
        return $this->belongsTo(Encomienda::class);
    }
}
