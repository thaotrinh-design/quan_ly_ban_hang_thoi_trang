<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->json('available_sizes')->nullable()->after('stock');
            $table->json('available_colors')->nullable()->after('available_sizes');
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->string('size')->nullable()->after('product_id');
            $table->string('color')->nullable()->after('size');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->string('size')->nullable()->after('product_id');
            $table->string('color')->nullable()->after('size');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->enum('order_type', ['delivery', 'pickup'])->default('delivery')->after('address');
        });

        $defaultSizes = json_encode(['S', 'M', 'L', 'XL']);
        $defaultColors = json_encode(['Đen', 'Trắng', 'Xám', 'Xanh navy']);

        DB::table('products')->update([
            'available_sizes' => $defaultSizes,
            'available_colors' => $defaultColors,
        ]);
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('order_type');
        });
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['size', 'color']);
        });
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropColumn(['size', 'color']);
        });
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['available_sizes', 'available_colors']);
        });
    }
};
