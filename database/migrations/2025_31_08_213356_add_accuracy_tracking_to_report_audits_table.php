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
        Schema::table('report_audits', function (Blueprint $table) {
            $table->unsignedBigInteger('original_creator_id')->nullable()->after('modifier_id');
            $table->unsignedBigInteger('record_id')->nullable()->after('original_creator_id');
            $table->string('record_type')->nullable()->after('record_id'); // 'defect_report' or 'purchase_order'
            
            // Foreign key for original creator
            $table->foreign('original_creator_id')->references('id')->on('users')->nullOnDelete();
            
            // Add index for better performance
            $table->index(['original_creator_id', 'record_type']);
            $table->index(['record_id', 'record_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('report_audits', function (Blueprint $table) {
            $table->dropForeign(['original_creator_id']);
            $table->dropIndex(['original_creator_id', 'record_type']);
            $table->dropIndex(['record_id', 'record_type']);
            $table->dropColumn(['original_creator_id', 'record_id', 'record_type']);
        });
    }
}; 