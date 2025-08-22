<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('vehicle_parts', function (Blueprint $table) {
            $table->dropUnique('vehicle_parts_name_unique'); // drop unique index
        });
    }

    public function down()
    {
        Schema::table('vehicle_parts', function (Blueprint $table) {
            $table->unique('name', 'vehicle_parts_name_unique'); // re-add unique index
        });
    }
};
