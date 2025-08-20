<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('vehicle_number')->nullable()->unique();
            $table->foreignId('location_id')->constrained('locations')->onDelete(null)->nullable();
            $table->foreignId('vehicle_category_id')->constrained('vehicle_categories')->onDelete(null)->nullable();
            $table->enum('condition', ['new', 'old'])->default('old')->nullable();
            $table->boolean('is_active')->default(0);
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
