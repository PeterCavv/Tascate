<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tasca_proposals', function (Blueprint $table) {
            $table->id();
            $table->string('tasca_name');
            $table->string('address');
            $table->string('telephone', 9);
            $table->string('cif', 9)->unique();
            $table->string('owner_name');
            $table->string('owner_email')->unique();
            $table->string('dni', 9)->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasca_proposals');
    }
};
