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
            $table->time('opening_time');
            $table->time('closing_time');
            $table->integer('capacity')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tascas');
    }
};
