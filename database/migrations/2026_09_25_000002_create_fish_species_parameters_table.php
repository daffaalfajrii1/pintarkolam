<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fish_species_parameters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fish_species_id')->constrained()->cascadeOnDelete();
            $table->string('code'); // ph, temperature, do
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

            $table->unique(['fish_species_id', 'code']);
            $table->index(['code', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fish_species_parameters');
    }
};
