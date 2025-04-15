<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
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
    }
};
