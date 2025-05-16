<?php

use App\Enums\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $roles = array_map(fn(Role $r) => $r->value, Role::cases());

        Schema::table('users', function (Blueprint $table) use ($roles) {
            $table->enum('role', $roles)->default(Role::CUSTOMER->value)->after('avatar');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
