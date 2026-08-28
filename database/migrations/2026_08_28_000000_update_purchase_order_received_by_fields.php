<?php

use App\Enums\PurchaseOrderReceivedBy;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Existing free-text values are intentionally standardized to Store Keeper.
        DB::table('purchase_orders')->update([
            'received_by' => PurchaseOrderReceivedBy::StoreKeeper->value,
        ]);

        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->string('received_by_other')->nullable()->after('received_by');
            $table->enum('received_by', [
                PurchaseOrderReceivedBy::StoreKeeper->value,
                PurchaseOrderReceivedBy::Other->value,
            ])->default(PurchaseOrderReceivedBy::StoreKeeper->value)->change();
        });
    }

    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->string('received_by')->change();
            $table->dropColumn('received_by_other');
        });
    }
};
