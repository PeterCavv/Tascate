<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tascas', function (Blueprint $table) {
            $table->string('picture')->nullable()->after('capacity');
        });
    }

    public function down(): void
    {
        Schema::table('tascas', function (Blueprint $table) {
            $table->dropColumn('picture');
        });
    }
};
