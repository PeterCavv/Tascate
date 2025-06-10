<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tascas', function (Blueprint $table) {
            $table->string('cif', 9)->unique()->after('telephone')
                ->comment('Unique identifier for the tasca, typically a tax identification number.');
        });
    }

    public function down(): void
    {
        Schema::table('tascas', function (Blueprint $table) {
            $table->dropColumn('cif');
        });
    }
};
