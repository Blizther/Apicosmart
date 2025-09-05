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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();

            // Usuario dueño del producto
            $table->foreignId('idUser')->constrained('users')->onDelete('cascade');

            $table->string('descripcion', 150)->index(); // index para búsquedas por nombre
            $table->string('unidadMedida', 25)->nullable(); // opcional si no siempre la llenas
            $table->integer('stock')->default(0); // valor por defecto
            $table->decimal('precio', 18, 2);

            $table->tinyInteger('estado')->default(1); // 1 = activo, 0 = inactivo
            $table->string('imagen')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
