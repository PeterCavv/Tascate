<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tasca_proposals', function (Blueprint $table) {
            $table->string('cif_picture_path')->after('cif');
            $table->string('dni_picture_path')->after('dni');
        });
    }

    public function down(): void
    {
        Schema::table('tasca_proposals', function (Blueprint $table) {
            $table->dropColumn('cif_picture_path');
            $table->dropColumn('dni_picture_path');
        });
    }
};
