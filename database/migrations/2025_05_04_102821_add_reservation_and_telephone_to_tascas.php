<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tascas', function (Blueprint $table) {
            $table->string('telephone', 9)->nullable()->after('address');
            $table->boolean('reservation')->default(false)->after('telephone');
            $table->integer('reservation_price')->default(0)->after('reservation');
        });
    }

    public function down(): void
    {
        Schema::table('tascas', function (Blueprint $table) {
            $table->dropColumn('reservas');
        });
    }
};
