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
            $table->json('before_changing_record_readable')->nullable()->after('before_changing_record');
            $table->json('after_changing_record_readable')->nullable()->after('after_changing_record');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('report_audits', function (Blueprint $table) {
            $table->dropColumn([
                'before_changing_record_readable',
                'after_changing_record_readable'
            ]);
        });
    }
};
