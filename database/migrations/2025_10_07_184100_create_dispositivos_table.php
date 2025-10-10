<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dispositivos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('idUser');                 // propietario
            $table->unsignedBigInteger('dispositivo_fabricado_id'); // FK al inventario
            $table->string('nombre', 120)->nullable();
            $table->tinyInteger('estado')->default(1);            // 1=activo
            $table->timestamps();

            $table->foreign('idUser')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('dispositivo_fabricado_id')->references('id')->on('dispositivos_fabricados')->onDelete('cascade');

            // un dispositivo fabricado solo puede tener un dueño
            $table->unique('dispositivo_fabricado_id', 'dispositivo_unico_por_duenio');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('dispositivos');
    }
};
