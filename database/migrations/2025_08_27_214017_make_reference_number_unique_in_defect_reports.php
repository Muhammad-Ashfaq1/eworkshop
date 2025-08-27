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
            $table->string('reference_number')->unique()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('defect_reports', function (Blueprint $table) {
            if (Schema::hasColumn('defect_reports', 'reference_number')) {
                $table->dropColumn('reference_number');
            }
        });
    }
};
