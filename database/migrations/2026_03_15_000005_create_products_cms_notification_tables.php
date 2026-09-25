<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('farmer_profile_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('fish_species_id')->constrained();
            $table->foreignId('cultivation_cycle_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('size_label')->nullable();
            $table->decimal('stock_kg', 12, 2)->nullable();
            $table->unsignedInteger('stock_pcs')->nullable();
            $table->enum('price_unit', ['kg', 'pcs'])->default('kg');
            $table->decimal('price', 12, 2);
            $table->decimal('min_order', 12, 2)->nullable();
            $table->string('location_label')->nullable();
            $table->text('description')->nullable();
            $table->string('whatsapp')->nullable();
            $table->enum('availability', ['available', 'sold_out'])->default('available');
            $table->enum('moderation_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->boolean('is_published')->default(false);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['moderation_status', 'availability', 'is_published']);
        });

        Schema::create('product_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('excerpt')->nullable();
            $table->longText('body');
            $table->string('cover_path')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->json('value')->nullable();
            $table->string('group')->default('general');
            $table->timestamps();
        });

        Schema::create('feature_flags', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->boolean('is_enabled')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('device_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('token');
            $table->string('platform')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'token']);
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        Schema::create('notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->boolean('water_critical')->default(true);
            $table->boolean('feeding_reminder')->default(true);
            $table->boolean('measurement_reminder')->default(true);
            $table->boolean('harvest_near')->default(true);
            $table->boolean('new_products')->default(true);
            $table->boolean('announcements')->default(true);
            $table->timestamps();
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action');
            $table->string('subject_type')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->json('properties')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
            $table->index(['subject_type', 'subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('notification_preferences');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('device_tokens');
        Schema::dropIfExists('feature_flags');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('product_photos');
        Schema::dropIfExists('products');
    }
};
