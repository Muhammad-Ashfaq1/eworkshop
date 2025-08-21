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
        Schema::create('defect_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->nullable()->constrained('vehicles')->onDelete('set null');
            $table->foreignId('location_id')->nullable()->constrained('locations')->onDelete('set null');
            $table->string('driver_name');
            $table->foreignId('fleet_manager_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('mvi_id')->nullable()->constrained('users')->onDelete('set null');
            $table->date('date');
            $table->string('attach_file')->nullable();
            $table->enum('type', ['defect_report', 'purchase_order'])->default('defect_report');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('defect_reports');
    }
};
