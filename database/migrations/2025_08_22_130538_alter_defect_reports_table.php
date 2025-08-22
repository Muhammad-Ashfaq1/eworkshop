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
            if (Schema::hasColumn('defect_reports', 'attach_file')) {
                $table->renameColumn('attach_file', 'attachment_url');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('defect_reports', function (Blueprint $table) {
            if (Schema::hasColumn('defect_reports', 'attachment_url')) {
                $table->renameColumn('attachment_url', 'attach_file');
            }
        });
    }
};
