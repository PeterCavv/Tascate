<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tascas', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->constrained('users')->onDelete('cascade');
            $table->string('address')->nullable()->after('name');
            $table->string('menu')->nullable()->after('address');
        });
    }

    public function down(): void
    {
        Schema::table('tascas', function (Blueprint $table) {
            $table->dropColumn('address');
            $table->dropColumn('menu');
        });
    }
};
