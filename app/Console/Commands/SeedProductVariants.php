<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Console\Command;

class SeedProductVariants extends Command
{
    protected $signature = 'products:seed-variants';
    protected $description = 'Tạo variant mặc định cho sản phẩm chưa có variant';

    public function handle(): int
    {
        $products = Product::withTrashed()
            ->with('variants')
            ->get()
            ->filter(fn ($p) => $p->variants->isEmpty());

        if ($products->isEmpty()) {
            $this->info('Tất cả sản phẩm đã có variant.');
            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar($products->count());
        $bar->start();

        foreach ($products as $product) {
            $sizes = $product->available_sizes ?? [];
            $colors = $product->available_colors ?? [];

            if (empty($sizes)) {
                $sizes = [$product->size ?: 'M'];
            }
            if (empty($colors)) {
                $colors = [$product->color ?: 'Đen'];
            }

            // Chia đều stock cho các variant
            $variantCount = count($sizes) * count($colors);
            $stockPerVariant = $variantCount > 0 ? intdiv($product->stock, $variantCount) : 0;
            $remainder = $product->stock % $variantCount;

            foreach ($sizes as $size) {
                foreach ($colors as $color) {
                    $stock = $stockPerVariant + ($remainder > 0 ? 1 : 0);
                    $remainder--;

                    ProductVariant::create([
                        'product_id' => $product->id,
                        'size' => $size,
                        'color' => $color,
                        'stock' => max(0, $stock),
                    ]);
                }
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Đã tạo variant cho {$products->count()} sản phẩm.");

        return self::SUCCESS;
    }
}
