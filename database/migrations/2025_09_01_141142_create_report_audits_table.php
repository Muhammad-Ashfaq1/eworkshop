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
    Schema::create('report_audits', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('modifier_id')->nullable();
        $table->json('before_changing_record')->nullable();
        $table->json('after_changing_record')->nullable();
        $table->enum('type', ['purchase_order', 'defect_report'])->nullable();

        // Foreign key
        $table->foreign('modifier_id')->references('id')->on('users')->nullOnDelete();

        $table->timestamps();
        $table->softDeletes();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_audits');
    }
};
