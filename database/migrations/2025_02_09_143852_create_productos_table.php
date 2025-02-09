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
            $table->string('codigo', 20);
            $table->integer('estado');
            $table->string('nombre', 100)->charset('utf8')->collation('utf8_general_ci');
            $table->string('busqueda', 100);
            $table->integer('stock');
            $table->integer('id_marca');
            $table->integer('id_tipo');
            $table->integer('id_bodega');
            $table->string('medidas', 20);
            $table->string('aro', 7)->nullable();
            $table->integer('aplicacion');
            $table->integer('p_sistema');
            $table->integer('p_venta');
            $table->string('img', 100);
            $table->tinyInteger('oferta');
            $table->integer('costo');
            $table->integer('top')->nullable();
            $table->integer('peso');
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
