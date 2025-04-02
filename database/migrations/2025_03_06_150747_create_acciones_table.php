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
        Schema::create('acciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idusuario')->constrained('usuarios')->onDelete('cascade');
            $table->timestamp('fechaalta')->useCurrent();
            $table->string('accion',36);
            $table->string('nombretecnico',36);
            $table->string('precio0');
            $table->string('rsi0');
            $table->string('precio1');
            $table->string('rsi1');
            $table->string('precio2');
            $table->string('rsi2');
            $table->string('precio3');
            $table->string('rsi3');
            $table->string('precio4');
            $table->string('rsi4');
            $table->string('precio5');
            $table->string('rsi5');
            $table->string('precio6');
            $table->string('rsi6');
            $table->string('precio7');
            $table->string('rsi7');
            $table->string('precio8');
            $table->string('rsi8');
            $table->string('precio9');
            $table->string('rsi9');
            $table->string('precio10');
            $table->string('rsi10');
            $table->string('precio11');
            $table->string('rsi11');
            $table->string('precio12');
            $table->string('rsi12');
            $table->string('precio13');
            $table->string('rsi13');
            $table->string('precio14');
            $table->string('rsi14');
            $table->string('precio15');
            $table->string('rsi15');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acciones');
    }
};
