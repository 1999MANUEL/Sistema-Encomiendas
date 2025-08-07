<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manifiestos', function (Blueprint $table) {
            $table->id();

            // Relaciones
            $table->foreignId('conductor_id')->constrained('conductores')->onDelete('cascade');
            $table->foreignId('vehiculo_id')->constrained('vehiculos')->onDelete('cascade');

            // Datos del manifiesto
            $table->dateTime('fecha_envio'); // Fecha y hora de envío
            $table->foreignId('sucursal_origen_id')->constrained('sucursales');
            $table->foreignId('sucursal_destino_id')->constrained('sucursales');

            // Campos opcionales
            $table->text('contenido')->nullable();
            //$table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manifiestos');
    }


};
