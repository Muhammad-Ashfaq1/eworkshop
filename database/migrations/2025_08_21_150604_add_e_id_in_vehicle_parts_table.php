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
        Schema::table('vehicle_parts', function (Blueprint $table) {
            if (! Schema::hasColumn('vehicle_parts', 'e_id')) {
                $table->integer('e_id')->nullable()->after('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicle_parts', function (Blueprint $table) {
            if (Schema::hasColumn('vehicle_parts', 'e_id')) {
                $table->dropColumn('e_id');
            }
        });
    }
};
