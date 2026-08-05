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
        // --- PRICING ENGINE ---
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('code', 3)->unique(); // USD, EUR
            $table->string('symbol', 10);
            $table->decimal('exchange_rate', 15, 8)->default(1.0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('pricing_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('target_type'); // event, ticket_type, global
            $table->unsignedBigInteger('target_id')->nullable();
            $table->string('rule_type'); // time_based, demand_based, category_based, bulk
            $table->json('conditions'); // {"remaining_lt": 50, "start_date": "..."}
            $table->decimal('adjustment_value', 10, 2);
            $table->enum('adjustment_type', ['fixed', 'percentage']);
            $table->integer('priority')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->enum('type', ['fixed', 'percentage']);
            $table->decimal('value', 10, 2);
            $table->integer('max_uses')->nullable();
            $table->integer('used_count')->default(0);
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->decimal('min_order_amount', 10, 2)->nullable();
            $table->timestamps();
        });

        // --- PAYMENT ENGINE ---
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3);
            $table->string('reason')->nullable();
            $table->string('status')->default('pending'); // pending, succeeded, failed
            $table->string('external_id')->nullable();
            $table->timestamps();
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('invoice_number')->unique();
            $table->string('pdf_path')->nullable();
            $table->dateTime('issued_at');
            $table->timestamps();
        });

        Schema::create('payment_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->nullable()->constrained()->onDelete('set null');
            $table->string('event_type');
            $table->json('payload');
            $table->timestamps();
        });

        // --- NOTIFICATION ENGINE ---
        Schema::create('notification_templates', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('channel'); // email, sms, push, whatsapp
            $table->string('subject')->nullable();
            $table->text('body');
            $table->json('translations')->nullable();
            $table->timestamps();
        });

        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('channel');
            $table->string('recipient');
            $table->string('status')->default('queued'); // queued, sent, delivered, failed
            $table->string('external_id')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
        });

        Schema::create('notification_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('template_slug');
            $table->json('payload');
            $table->dateTime('scheduled_at');
            $table->string('status')->default('pending');
            $table->timestamps();
        });

        // --- ANALYTICS ENGINE ---
        Schema::create('daily_stats', function (Blueprint $table) {
            $table->id();
            $table->date('date')->index();
            $table->foreignId('event_id')->nullable()->constrained()->onDelete('cascade');
            $table->decimal('revenue', 15, 2)->default(0);
            $table->integer('tickets_sold')->default(0);
            $table->integer('attendance')->default(0);
            $table->integer('refunds_count')->default(0);
            $table->timestamps();

            $table->unique(['date', 'event_id']);
        });

        // --- ADDITIONAL SECURITY (Ticket Engine) ---
        Schema::create('ticket_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->onDelete('cascade');
            $table->foreignId('from_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('to_user_id')->constrained('users')->onDelete('cascade');
            $table->string('status')->default('completed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_transfers');
        Schema::dropIfExists('daily_stats');
        Schema::dropIfExists('notification_schedules');
        Schema::dropIfExists('notification_logs');
        Schema::dropIfExists('notification_templates');
        Schema::dropIfExists('payment_logs');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('refunds');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('pricing_rules');
        Schema::dropIfExists('currencies');
    }
};
