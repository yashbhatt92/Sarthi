<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services')->restrictOnDelete();
            $table->foreignId('customer_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('assigned_staff_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->index();
            $table->string('title');
            $table->text('description');
            $table->boolean('chat_enabled')->default(false);
            $table->timestamp('chat_closed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('request_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_request_id')->constrained('service_requests')->cascadeOnDelete();
            $table->foreignId('sender_id')->constrained('users')->restrictOnDelete();
            $table->text('body');
            $table->timestamp('seen_by_staff_at')->nullable();
            $table->timestamp('seen_by_customer_at')->nullable();
            $table->timestamps();
        });

        Schema::create('cab_custmr_prfl', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->timestamps();
        });

        Schema::create('cab_custmr_occup', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('occupation')->nullable();
            $table->string('company')->nullable();
            $table->timestamps();
        });

        Schema::create('cab_cust_fin_info', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('pan')->nullable();
            $table->string('income_band')->nullable();
            $table->timestamps();
        });

        Schema::create('cab_custmr_doc', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->string('path');
            $table->timestamps();
        });

        Schema::create('cab_staff_prfl', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('department')->nullable();
            $table->string('designation')->nullable();
            $table->timestamps();
        });

        Schema::create('cab_todos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->boolean('is_done')->default(false);
            $table->timestamps();
        });

        Schema::create('cab_meets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizer_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->timestamp('scheduled_for');
            $table->string('meeting_link')->nullable();
            $table->timestamps();
        });

        Schema::create('cab_aff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('code')->unique();
            $table->timestamps();
        });

        Schema::create('affiliate_audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('affiliate_id')->constrained('cab_aff')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->string('event');
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('cab_invc', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_request_id')->constrained('service_requests')->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('status')->default('draft');
            $table->timestamp('issued_at')->nullable();
            $table->timestamps();
        });

        Schema::create('cab_pymt', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('cab_invc')->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('status')->default('initiated');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        Schema::create('support_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->text('subject');
            $table->text('message');
            $table->string('status')->default('open');
            $table->timestamps();
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('support_messages');
        Schema::dropIfExists('cab_pymt');
        Schema::dropIfExists('cab_invc');
        Schema::dropIfExists('affiliate_audits');
        Schema::dropIfExists('cab_aff');
        Schema::dropIfExists('cab_meets');
        Schema::dropIfExists('cab_todos');
        Schema::dropIfExists('cab_staff_prfl');
        Schema::dropIfExists('cab_custmr_doc');
        Schema::dropIfExists('cab_cust_fin_info');
        Schema::dropIfExists('cab_custmr_occup');
        Schema::dropIfExists('cab_custmr_prfl');
        Schema::dropIfExists('request_messages');
        Schema::dropIfExists('service_requests');
        Schema::dropIfExists('services');
    }
};
