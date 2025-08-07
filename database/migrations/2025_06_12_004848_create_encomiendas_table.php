<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('encomiendas', function (Blueprint $table) {
            $table->id();
             $table->string('numero_guia',100)->unique();
            // Remitente
            $table->text('nombre_remitente');
            $table->text('direccion_remitente');
            $table->text('contacto_remitente');
            // Destinatario
            $table->text('nombre_destinatario');
            $table->text('direccion_destinatario');
            $table->text('contacto_destinatario');

            $table->string('descripcion_contenido'); // Breve descripción de lo que se envía (ej: "1 CAJA")
            $table->integer('cantidad_paquetes');   // Nuevo campo: "Cantidad" en la factura
            $table->decimal('valor_declarado', 10, 2)->nullable(); // "Valor declarado" en la factura
            //primero manifiesto sino error
          $table->foreignId('manifiesto_id')->nullable()->constrained('manifiestos')->nullOnDelete();


            // Mantener tinyInteger, y ahora los valores los definirás en el Enum EncomiendaTipo
            //$table->tinyInteger('tipo');

            $table->foreignId('sucursal_origen_id')->constrained('sucursales');
            $table->foreignId('sucursal_destino_id')->constrained('sucursales');

            // Dimensiones
            $table->decimal('peso', 8, 2); // 123456.78 kg
            $table->decimal('altura', 8, 2); // cm
            $table->decimal('ancho', 8, 2); // cm
            $table->decimal('largo', 8, 2); // cm

            $table->decimal('precio', 10, 2);
            // Mantener tinyInteger y default, el Enum lo interpretará.
            // Los valores de '1=registrado, 2=en tránsito, etc.' irán al Enum EncomiendaEstado
            $table->tinyInteger('estado')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encomiendas');
    }
};