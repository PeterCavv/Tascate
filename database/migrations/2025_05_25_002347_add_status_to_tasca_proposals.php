<?php

use App\Enums\ManageStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $statuses = array_map(fn(ManageStatus $r) => $r->value, ManageStatus::cases());

        Schema::table('tasca_proposals', function (Blueprint $table) use ($statuses) {
            $table->enum('status', $statuses)->default(ManageStatus::PENDING->value)->after('dni');
        });
    }

    public function down(): void
    {
        Schema::table('tasca_proposals', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
