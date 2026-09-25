<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('farmer_profiles', function (Blueprint $table) {
            $table->string('shop_name')->nullable()->after('business_name');
            $table->string('shop_logo_path')->nullable()->after('bio');
            $table->string('shop_cover_path')->nullable()->after('shop_logo_path');
            $table->text('shop_description')->nullable()->after('shop_cover_path');
            $table->text('storefront_rejection_reason')->nullable()->after('rejection_reason');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->text('rejection_reason')->nullable()->after('moderation_status');
            $table->foreignId('moderated_by')->nullable()->after('rejection_reason')->constrained('users')->nullOnDelete();
            $table->timestamp('moderated_at')->nullable()->after('moderated_by');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('moderated_by');
            $table->dropColumn(['rejection_reason', 'moderated_at']);
        });

        Schema::table('farmer_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'shop_name',
                'shop_logo_path',
                'shop_cover_path',
                'shop_description',
                'storefront_rejection_reason',
            ]);
        });
    }
};
