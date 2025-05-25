<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('tascas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->time('opening_time')->default('12:00');
            $table->time('closing_time')->default('23:00');
            $table->integer('capacity')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tascas');
    }
};
