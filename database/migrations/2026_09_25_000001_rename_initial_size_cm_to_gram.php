<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('cultivation_cycles', 'initial_size_cm')) {
            return;
        }

        if (! Schema::hasColumn('cultivation_cycles', 'initial_size_gram')) {
            Schema::table('cultivation_cycles', function (Blueprint $table) {
                $table->decimal('initial_size_gram', 8, 2)->nullable()->after('seed_count');
            });
        }

        DB::table('cultivation_cycles')->update([
            'initial_size_gram' => DB::raw('initial_size_cm'),
        ]);

        Schema::table('cultivation_cycles', function (Blueprint $table) {
            $table->dropColumn('initial_size_cm');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('cultivation_cycles', 'initial_size_gram')) {
            return;
        }

        if (! Schema::hasColumn('cultivation_cycles', 'initial_size_cm')) {
            Schema::table('cultivation_cycles', function (Blueprint $table) {
                $table->decimal('initial_size_cm', 8, 2)->nullable()->after('seed_count');
            });
        }

        DB::table('cultivation_cycles')->update([
            'initial_size_cm' => DB::raw('initial_size_gram'),
        ]);

        Schema::table('cultivation_cycles', function (Blueprint $table) {
            $table->dropColumn('initial_size_gram');
        });
    }
};
