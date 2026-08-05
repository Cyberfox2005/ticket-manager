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
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->constrained()->onDelete('cascade');
            $table->string('section');
            $table->string('row');
            $table->string('number');
            $table->string('status')->default('available'); // available, blocked
            $table->timestamps();

            $table->unique(['venue_id', 'section', 'row', 'number']);
        });

        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('seat_id')->nullable()->constrained()->onDelete('set null');
            $table->dateTime('expires_at');
            $table->string('status')->default('pending'); // pending, confirmed, expired
            $table->timestamps();

            $table->index(['event_id', 'status']);
        });

        // Add seat_id to tickets to support seat mapping
        Schema::table('tickets', function (Blueprint $table) {
            $table->foreignId('seat_id')->nullable()->after('user_id')->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropConstrainedForeignId('seat_id');
        });
        Schema::dropIfExists('reservations');
        Schema::dropIfExists('seats');
    }
};
