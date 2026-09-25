<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feeding_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cultivation_cycle_id')->constrained()->cascadeOnDelete();
            $table->time('feed_time');
            $table->string('feed_type')->nullable();
            $table->decimal('amount_kg', 10, 3)->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('feeding_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cultivation_cycle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('feeding_schedule_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('fed_at');
            $table->string('feed_type')->nullable();
            $table->decimal('amount_kg', 10, 3);
            $table->decimal('leftover_kg', 10, 3)->nullable();
            $table->enum('status', ['done', 'skipped', 'partial'])->default('done');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['cultivation_cycle_id', 'fed_at']);
        });

        Schema::create('growth_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cultivation_cycle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('sampled_at');
            $table->unsignedInteger('sample_count')->nullable();
            $table->decimal('avg_weight_gram', 10, 2);
            $table->decimal('avg_length_cm', 8, 2)->nullable();
            $table->unsignedInteger('estimated_alive')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('mortality_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cultivation_cycle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('recorded_at');
            $table->unsignedInteger('death_count');
            $table->string('suspected_cause')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('fish_health_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cultivation_cycle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('observed_at');
            $table->string('symptoms')->nullable();
            $table->unsignedInteger('affected_count')->nullable();
            $table->unsignedInteger('death_count')->default(0);
            $table->string('photo_path')->nullable();
            $table->string('suspected_cause')->nullable();
            $table->text('action_taken')->nullable();
            $table->enum('status', ['open', 'monitoring', 'resolved'])->default('open');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('harvest_estimates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cultivation_cycle_id')->constrained()->cascadeOnDelete();
            $table->date('estimated_harvest_date')->nullable();
            $table->unsignedInteger('estimated_fish_count')->nullable();
            $table->decimal('estimated_total_weight_kg', 12, 2)->nullable();
            $table->decimal('estimated_value', 14, 2)->nullable();
            $table->decimal('survival_rate', 5, 2)->nullable();
            $table->enum('readiness_status', ['not_ready', 'near', 'ready'])->default('not_ready');
            $table->json('assumptions')->nullable();
            $table->timestamp('calculated_at');
            $table->timestamps();
        });

        Schema::create('harvest_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cultivation_cycle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('harvested_at');
            $table->unsignedInteger('fish_count')->nullable();
            $table->decimal('total_weight_kg', 12, 2);
            $table->decimal('avg_weight_gram', 10, 2)->nullable();
            $table->decimal('selling_price', 14, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('harvest_records');
        Schema::dropIfExists('harvest_estimates');
        Schema::dropIfExists('fish_health_logs');
        Schema::dropIfExists('mortality_logs');
        Schema::dropIfExists('growth_records');
        Schema::dropIfExists('feeding_logs');
        Schema::dropIfExists('feeding_schedules');
    }
};
