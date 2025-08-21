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
        Schema::create('works', function (Blueprint $table) {
            $table->id();
            $table->foreignId('defect_report_id')->nullable()->constrained('defect_reports')->onDelete('set null');
            $table->string('work', 300)->nullable(); 
            $table->enum('type', ['defect', 'purchase_order']); 
            $table->integer('quantity')->nullable(); 
            $table->foreignId('vehicle_part_id')->nullable()->constrained('vehicle_parts')->onDelete('set null'); 
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('works');
    }
};
