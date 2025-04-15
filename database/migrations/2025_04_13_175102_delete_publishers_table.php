<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['publisher_id']);
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['publisher_id']);
        });

        Schema::table('likes', function (Blueprint $table) {
            $table->dropForeign(['publisher_id']);
        });

        Schema::dropIfExists('publishers');
    }
    public function down(): void
    {
        Schema::create('publishers', function (Blueprint $table) {
            $table->id();
            $table->enum('publisher_type', ['user', 'tasca'])->default('user');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('tasca_id')->constrained('tascas')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->foreign('publisher_id')->references('id')->on('publishers')->onDelete('cascade');
        });

        Schema::table('likes', function (Blueprint $table) {
            $table->foreign('publisher_id')->references('id')->on('publishers')->onDelete('cascade');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->foreign('publisher_id')->references('id')->on('publishers')->onDelete('cascade');
        });
    }
};
