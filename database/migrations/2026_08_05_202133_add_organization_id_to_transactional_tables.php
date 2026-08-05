<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = ['orders', 'tickets', 'payments', 'seats', 'reservations'];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                if (!Schema::hasColumn($table->getTable(), 'organization_id')) {
                    $table->foreignId('organization_id')->nullable()->after('id')->constrained()->onDelete('cascade');
                }
            });
        }
    }

    public function down(): void
    {
        $tables = ['orders', 'tickets', 'payments', 'seats', 'reservations'];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropConstrainedForeignId('organization_id');
            });
        }
    }
};
