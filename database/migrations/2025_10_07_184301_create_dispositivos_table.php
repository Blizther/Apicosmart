<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dispositivos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('idUser'); // FK a users.id (coherente con tus otras tablas)
            $table->string('serial', 64)->unique(); // código único del sensor (ej. XDF4-ASER4-45AL-12PL)
            $table->string('api_key', 80)->unique(); // clave secreta por dispositivo (para el ESP32)
            $table->string('nombre', 120)->nullable(); // etiqueta amigable
            $table->tinyInteger('activo')->default(1);
            $table->timestamps();

            $table->foreign('idUser')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dispositivos');
    }
};
