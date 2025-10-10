<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lecturas_sensores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('dispositivo_id');
            $table->timestamp('ts')->useCurrent();
            $table->decimal('humedad', 5, 2)->nullable();
            $table->decimal('peso', 8, 3)->nullable();
            $table->decimal('temperatura', 5, 2)->nullable();
            $table->timestamps();

            $table->foreign('dispositivo_id')->references('id')->on('dispositivos')->onDelete('cascade');
            $table->index(['dispositivo_id', 'ts']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('lecturas_sensores');
    }
};
