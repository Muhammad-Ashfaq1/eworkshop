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
        Schema::table('defect_reports', function (Blueprint $table) {
            if(Schema::hasColumn('defect_reports', 'fleet_manager_id')) {
                // Drop existing foreign keys
                $table->dropForeign(['fleet_manager_id']);
                $table->dropForeign(['mvi_id']);
                $table->dropColumn(['fleet_manager_id', 'mvi_id']);
            }


            $table->foreignId('fleet_manager_id')->nullable()
                  ->constrained('fleet_managers')
                  ->onDelete('set null');

            $table->foreignId('mvi_id')
                  ->nullable()
                  ->constrained('fleet_managers')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('defect_reports', function (Blueprint $table) {
            // Drop the new foreign keys
            $table->dropForeign(['fleet_manager_id']);
            $table->dropForeign(['mvi_id']);

            // Drop the new columns
            $table->dropColumn(['fleet_manager_id', 'mvi_id']);

            // Recreate the original columns pointing to users table
            $table->foreignId('fleet_manager_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');

            $table->foreignId('mvi_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
        });
    }
};
