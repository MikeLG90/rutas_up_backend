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
    Schema::create('documentos', function (Blueprint $table) {
        $table->id();
        $table->string('nombre'); 
        $table->date('fecha_expiracion')->nullable(); 
        $table->string('ruta_archivo'); 
        
        // RELACIÓN POLIMÓRFICA ADAPTADA:
        // Crea 'documentable_type' y 'documentable_id' (que usará INT/BIGINT, coincidiendo con vehiculo_id/chofer_id)
        $table->morphs('documentable'); 
        
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos');
    }
};
