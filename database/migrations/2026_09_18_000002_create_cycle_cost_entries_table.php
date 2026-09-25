<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cycle_cost_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cultivation_cycle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('category');
            $table->string('label')->nullable();
            $table->decimal('amount', 14, 2);
            $table->date('recorded_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['cultivation_cycle_id', 'category']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cycle_cost_entries');
    }
};
