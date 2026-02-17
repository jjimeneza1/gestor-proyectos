<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTareasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tareas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('proyecto_id')
                  ->constrained('proyectos')
                  ->cascadeOnDelete();

            $table->string('titulo', 200);
            $table->text('descripcion')->nullable();

            $table->enum('prioridad', ['alta', 'media', 'baja'])
                  ->default('media');

            $table->enum('estado', ['backlog', 'en_progreso', 'testing', 'terminada'])
                  ->default('backlog');

            $table->smallInteger('orden')->default(0);

            $table->timestamps();

            // Índice compuesto para consultas de tablero Kanban
            $table->index(['estado', 'orden']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tareas');
    }
}
