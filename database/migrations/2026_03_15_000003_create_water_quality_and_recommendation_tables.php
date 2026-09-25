<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('water_quality_parameters', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('unit')->nullable();
            $table->decimal('min_value', 10, 3)->nullable();
            $table->decimal('max_value', 10, 3)->nullable();
            $table->decimal('ideal_min', 10, 3)->nullable();
            $table->decimal('ideal_max', 10, 3)->nullable();
            $table->unsignedTinyInteger('weight')->default(10);
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('water_quality_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cultivation_cycle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('pond_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('ph', 5, 2)->nullable();
            $table->decimal('temperature_c', 5, 2)->nullable();
            $table->decimal('dissolved_oxygen', 6, 2)->nullable();
            $table->decimal('ammonia', 8, 3)->nullable();
            $table->decimal('nitrite', 8, 3)->nullable();
            $table->decimal('turbidity', 8, 2)->nullable();
            $table->decimal('alkalinity', 8, 2)->nullable();
            $table->decimal('salinity', 8, 2)->nullable();
            $table->string('visual_condition')->nullable();
            $table->string('odor')->nullable();
            $table->text('notes')->nullable();
            $table->string('photo_path')->nullable();
            $table->timestamp('measured_at');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['cultivation_cycle_id', 'measured_at']);
        });

        Schema::create('pond_health_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pond_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cultivation_cycle_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('water_quality_log_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedTinyInteger('score');
            $table->enum('category', ['normal', 'waspada', 'kritis']);
            $table->json('factors')->nullable();
            $table->timestamp('calculated_at');
            $table->timestamps();
            $table->index(['pond_id', 'calculated_at']);
        });

        Schema::create('recommendation_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fish_species_id')->nullable()->constrained()->nullOnDelete();
            $table->string('parameter_code');
            $table->string('phase')->nullable();
            $table->decimal('min_value', 10, 3)->nullable();
            $table->decimal('max_value', 10, 3)->nullable();
            $table->enum('severity', ['info', 'warning', 'critical'])->default('warning');
            $table->string('title');
            $table->text('advice');
            $table->unsignedInteger('priority')->default(100);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['parameter_code', 'is_active']);
        });

        Schema::create('recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cultivation_cycle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('water_quality_log_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('recommendation_rule_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('severity', ['info', 'warning', 'critical'])->default('info');
            $table->string('title');
            $table->text('advice');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
            $table->index(['cultivation_cycle_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recommendations');
        Schema::dropIfExists('recommendation_rules');
        Schema::dropIfExists('pond_health_scores');
        Schema::dropIfExists('water_quality_logs');
        Schema::dropIfExists('water_quality_parameters');
    }
};
