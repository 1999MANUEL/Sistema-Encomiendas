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
        Schema::create('conductor_vehiculo', function (Blueprint $table) {
        
            $table->unsignedBigInteger('conductor_id');
            $table->unsignedBigInteger('vehiculo_id');
            $table->foreign('conductor_id')->references('id')->on('conductores')->onDelete('cascade');
            $table->foreign('vehiculo_id')->references('id')->on('vehiculos')->onDelete('cascade');

         
            $table->primary(['conductor_id', 'vehiculo_id']);

            $table->date('fecha_inicio_asignacion');
            $table->date('fecha_fin_asignacion')->nullable(); 
      
            $table->tinyInteger('rol_conductor')->default(1); 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conductor_vehiculo');
    }
};