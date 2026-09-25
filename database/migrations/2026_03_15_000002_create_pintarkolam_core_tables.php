<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('farmer_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('business_name')->nullable();
            $table->string('owner_name')->nullable();
            $table->text('address')->nullable();
            $table->string('village')->nullable();
            $table->string('district')->nullable();
            $table->string('regency')->default('Rejang Lebong');
            $table->string('province')->default('Bengkulu');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->boolean('hide_exact_location')->default(true);
            $table->string('whatsapp')->nullable();
            $table->text('bio')->nullable();
            $table->enum('verification_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('storefront_status', ['inactive', 'pending', 'approved', 'rejected', 'suspended'])->default('inactive');
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['verification_status', 'storefront_status']);
            $table->index(['district', 'village']);
        });

        Schema::create('fish_species', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('scientific_name')->nullable();
            $table->text('description')->nullable();
            $table->integer('typical_harvest_days')->nullable();
            $table->decimal('typical_harvest_weight_gram', 10, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['province', 'regency', 'district', 'village']);
            $table->foreignId('parent_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamps();
            $table->index(['type', 'name']);
        });

        Schema::create('ponds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('farmer_profile_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->enum('type', ['beton', 'terpal', 'tanah', 'bioflok', 'lainnya'])->default('terpal');
            $table->decimal('area_m2', 10, 2)->nullable();
            $table->decimal('depth_m', 8, 2)->nullable();
            $table->decimal('volume_m3', 12, 2)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->boolean('hide_exact_location')->default(true);
            $table->text('notes')->nullable();
            $table->enum('status', ['active', 'inactive', 'maintenance'])->default('active');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['user_id', 'status']);
        });

        Schema::create('pond_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pond_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->string('caption')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        Schema::create('cultivation_cycles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pond_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('fish_species_id')->constrained();
            $table->string('name');
            $table->date('stocking_date');
            $table->unsignedInteger('seed_count');
            $table->decimal('initial_size_gram', 8, 2)->nullable();
            $table->string('seed_source')->nullable();
            $table->decimal('target_size_gram', 10, 2)->nullable();
            $table->date('target_harvest_date')->nullable();
            $table->enum('status', ['preparation', 'active', 'near_harvest', 'completed', 'failed'])->default('preparation');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['user_id', 'status']);
            $table->index(['pond_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cultivation_cycles');
        Schema::dropIfExists('pond_photos');
        Schema::dropIfExists('ponds');
        Schema::dropIfExists('locations');
        Schema::dropIfExists('fish_species');
        Schema::dropIfExists('farmer_profiles');
    }
};
