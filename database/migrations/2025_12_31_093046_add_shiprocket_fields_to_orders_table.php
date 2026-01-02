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
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'shiprocket_order_id')) {
                $table->string('shiprocket_order_id')->nullable()->after('transaction_id');
            }
            if (!Schema::hasColumn('orders', 'shiprocket_shipment_id')) {
                $table->string('shiprocket_shipment_id')->nullable()->after('shiprocket_order_id');
            }
            if (!Schema::hasColumn('orders', 'awb_code')) {
                $table->string('awb_code')->nullable()->after('shiprocket_shipment_id');
            }
            if (!Schema::hasColumn('orders', 'tracking_url')) {
                $table->string('tracking_url')->nullable()->after('awb_code');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['shiprocket_order_id', 'shiprocket_shipment_id', 'awb_code', 'tracking_url']);
        });
    }
};
