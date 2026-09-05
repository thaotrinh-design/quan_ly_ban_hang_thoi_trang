<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('vnpay_txn_ref')->nullable()->after('payment_method');
            $table->string('vnpay_transaction_no')->nullable()->after('vnpay_txn_ref');
            $table->string('vnpay_response_code')->nullable()->after('vnpay_transaction_no');
            $table->string('vnpay_bank_code')->nullable()->after('vnpay_response_code');
            $table->timestamp('paid_at')->nullable()->after('vnpay_bank_code');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'vnpay_txn_ref',
                'vnpay_transaction_no',
                'vnpay_response_code',
                'vnpay_bank_code',
                'paid_at',
            ]);
        });
    }
};
