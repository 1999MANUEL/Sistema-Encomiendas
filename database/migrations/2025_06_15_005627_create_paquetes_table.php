<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paquetes', function (Blueprint $table) {
            $table->id();

            // Relación con la encomienda
            $table->foreignId('encomienda_id')->constrained('encomiendas')->onDelete('cascade');

            $table->string('descripcion'); // Ej: "Caja con libros de medicina"
            $table->decimal('peso', 8, 2); // En kilogramos
            $table->decimal('altura', 8, 2); // En cm
            $table->decimal('ancho', 8, 2); // En cm
            $table->decimal('largo', 8, 2); // En cm
            $table->decimal('precio', 10, 2); // Subtotal o costo estimado del paquete

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paquetes');
    }
};

