<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tascas', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->default(37.961755832628626)->after('picture');
            $table->decimal('longitude', 11, 8)->default( -1.1340977493324722)->after('latitude');
        });
    }

    public function down(): void
    {
        Schema::table('tascas', function (Blueprint $table) {
            //
        });
    }
};
