<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cultivation_cycles', function (Blueprint $table) {
            $table->decimal('seed_cost', 14, 2)->default(0)->after('notes');
            $table->decimal('feed_cost', 14, 2)->default(0)->after('seed_cost');
            $table->decimal('electricity_cost', 14, 2)->default(0)->after('feed_cost');
            $table->decimal('medicine_cost', 14, 2)->default(0)->after('electricity_cost');
            $table->decimal('other_cost', 14, 2)->default(0)->after('medicine_cost');
            $table->decimal('estimated_revenue', 14, 2)->default(0)->after('other_cost');
        });

        Schema::table('ponds', function (Blueprint $table) {
            $table->uuid('public_token')->nullable()->unique()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('cultivation_cycles', function (Blueprint $table) {
            $table->dropColumn([
                'seed_cost', 'feed_cost', 'electricity_cost',
                'medicine_cost', 'other_cost', 'estimated_revenue',
            ]);
        });

        Schema::table('ponds', function (Blueprint $table) {
            $table->dropColumn('public_token');
        });
    }
};
