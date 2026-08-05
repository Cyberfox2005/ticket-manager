<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = [
            'organizations', 'venues', 'events', 'ticket_types',
            'orders', 'tickets', 'payments', 'seats', 'reservations'
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                if (!Schema::hasColumn($table->getTable(), 'deleted_at')) {
                    $table->softDeletes();
                }
                if (!Schema::hasColumn($table->getTable(), 'created_by')) {
                    $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
                }
                if (!Schema::hasColumn($table->getTable(), 'updated_by')) {
                    $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
                }
            });
        }

        // Add Multi-tenant and scalability indexes
        Schema::table('orders', function (Blueprint $table) {
            $table->index(['user_id', 'status']);
            $table->jsonb('billing_details')->nullable(); // Using jsonb for Postgres readiness
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->index(['qr_hash', 'validated_at']);
            $table->boolean('is_revoked')->default(false);
            $table->string('revocation_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'organizations', 'venues', 'events', 'ticket_types',
            'orders', 'tickets', 'payments', 'seats', 'reservations'
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropSoftDeletes();
                $table->dropConstrainedForeignId('created_by');
                $table->dropConstrainedForeignId('updated_by');
            });
        }
    }
};
