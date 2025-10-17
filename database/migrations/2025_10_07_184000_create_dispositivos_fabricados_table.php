<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dispositivos_fabricados', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('serial', 64)->unique();     // único
            $table->string('api_key_hash', 255);        // Hash::make() de la API-KEY
            $table->tinyInteger('estado')->default(0);  // 0=inactivo al crear, 1=activo al vincular
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('dispositivos_fabricados');
    }
};
