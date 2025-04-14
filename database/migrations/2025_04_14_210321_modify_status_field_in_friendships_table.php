<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('friendships', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->string('status')->default('pending');
        });
    }

    public function down(): void
    {
        Schema::table('friendships', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
        });
    }
};
