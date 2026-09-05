<?php

namespace Database\Seeders;

use App\Helpers\ProductImageHelper;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $discountOptions = [10, 15, 20, 25, 30, 35, 40];

        $sizesByCategory = [
            'Áo thun nam' => ['S', 'M', 'L', 'XL', 'XXL'],
            'Áo sơ mi' => ['S', 'M', 'L', 'XL'],
            'Áo khoác' => ['M', 'L', 'XL', 'XXL'],
            'Quần jean' => ['28', '29', '30', '31', '32', '33'],
            'Quần short' => ['S', 'M', 'L', 'XL'],
            'Váy đầm' => ['S', 'M', 'L', 'XL'],
            'Áo len & Hoodie' => ['M', 'L', 'XL', 'XXL', 'Free size'],
            'Giày dép' => ['38', '39', '40', '41', '42', '43'],
            'Túi xách' => ['Free size'],
            'Phụ kiện' => ['Free size'],
        ];
        $colorsByCategory = [
            'Áo thun nam' => ['Đen', 'Trắng', 'Xám', 'Xanh navy', 'Đỏ'],
            'Áo sơ mi' => ['Trắng', 'Xanh navy', 'Be', 'Đen', 'Hồng pastel'],
            'Áo khoác' => ['Đen', 'Xanh denim', 'Be', 'Nâu', 'Xám'],
            'Quần jean' => ['Xanh đậm', 'Xanh nhạt', 'Đen', 'Xám'],
            'Quần short' => ['Đen', 'Be', 'Xanh navy', 'Trắng'],
            'Váy đầm' => ['Đen', 'Trắng', 'Đỏ', 'Hồng', 'Hoa nhí'],
            'Áo len & Hoodie' => ['Đen', 'Xám', 'Be', 'Xanh navy', 'Trắng'],
            'Giày dép' => ['Trắng', 'Đen', 'Nâu', 'Xám', 'Đỏ'],
            'Túi xách' => ['Đen', 'Nâu', 'Be', 'Hồng', 'Xanh navy'],
            'Phụ kiện' => ['Đen', 'Nâu', 'Be', 'Đỏ', 'Vàng'],
        ];

        $productsByCategory = [
            'Áo thun nam' => [
                ['Áo thun basic cotton đen', 199000, 'Áo thun cotton 100%, form regular, thoáng mát.'],
                ['Áo thun oversize trắng', 249000, 'Áo thun oversize phong cách streetwear.'],
                ['Áo thun in graphic vintage', 279000, 'Họa tiết retro, chất liệu cotton mềm.'],
                ['Áo thun polo nam navy', 329000, 'Áo polo cổ bẻ, phù hợp đi làm và dạo phố.'],
                ['Áo thun tay dài basic', 259000, 'Áo thun tay dài mùa thu đông.'],
                ['Áo thun nam phối viền', 219000, 'Thiết kế phối viền cổ tay năng động.'],
                ['Áo thun nam dry-fit thể thao', 289000, 'Vải thấm hút mồ hôi, co giãn tốt.'],
                ['Áo thun slim fit be', 229000, 'Áo thun slim fit tông be nhẹ nhàng, dễ phối đồ.'],
                ['Áo thun oversize xanh pastel', 259000, 'Áo thun oversize màu pastel trẻ trung.'],
                ['Áo thun in slogan đen', 269000, 'Áo thun in slogan tối giản, cá tính.'],
            ],
            'Áo sơ mi' => [
                ['Áo sơ mi trắng công sở', 399000, 'Sơ mi trắng form slim fit, dễ phối đồ.'],
                ['Áo sơ mi kẻ caro xanh', 359000, 'Sơ mi kẻ caro phong cách casual.'],
                ['Áo sơ mi linen be', 429000, 'Chất liệu linen thoáng mát mùa hè.'],
                ['Áo sơ mi dài tay đen', 379000, 'Sơ mi đen thanh lịch, form regular.'],
                ['Áo sơ mi họa tiết hoa', 389000, 'Họa tiết hoa nhỏ nữ tính.'],
                ['Áo sơ mi denim', 449000, 'Sơ mi chất liệu denim bền đẹp.'],
                ['Áo sơ mi sọc xanh', 369000, 'Sơ mi sọc dọc xanh hiện đại, gọn dáng.'],
                ['Áo sơ mi lụa trắng', 459000, 'Sơ mi lụa trắng mềm mịn, sang trọng.'],
                ['Áo sơ mi denim wash', 439000, 'Sơ mi denim wash cá tính, phóng khoáng.'],
            ],
            'Áo khoác' => [
                ['Áo khoác denim xanh', 599000, 'Áo khoác jean classic unisex.'],
                ['Áo khoác bomber đen', 649000, 'Bomber jacket phong cách pilot.'],
                ['Áo blazer nữ be', 799000, 'Blazer công sở thanh lịch.'],
                ['Áo khoác gió chống nước', 549000, 'Áo gió nhẹ, tiện dụng du lịch.'],
                ['Áo khoác da PU', 899000, 'Khoác da PU bóng bẩy.'],
                ['Áo cardigan len', 479000, 'Cardigan ấm áp mùa đông.'],
                ['Áo khoác bomber xám', 669000, 'Bomber xám basic, dễ phối streetwear.'],
                ['Áo khoác denim xanh nhạt', 629000, 'Khoác denim xanh nhạt trẻ trung.'],
                ['Áo blazer đen basic', 819000, 'Blazer đen tối giản, thanh lịch.'],
            ],
            'Quần jean' => [
                ['Quần jean slim fit xanh đậm', 499000, 'Jean slim fit co giãn nhẹ.'],
                ['Quần jean baggy nữ', 529000, 'Jean baggy phong cách Y2K.'],
                ['Quần jean ống rộng', 549000, 'Jean ống rộng unisex.'],
                ['Quần jean rách gối', 479000, 'Jean rách gối cá tính.'],
                ['Quần jean đen basic', 459000, 'Jean đen dễ phối mọi outfit.'],
                ['Quần jean short nữ', 349000, 'Jean short mùa hè.'],
                ['Quần jean baggy rách gối', 539000, 'Jean baggy phối rách gối cá tính.'],
                ['Quần jean ống rộng be', 559000, 'Jean ống rộng tông be hiện đại.'],
                ['Quần jean đen basic slim', 469000, 'Jean đen slim basic, gọn dáng.'],
            ],
            'Quần short' => [
                ['Quần short thể thao đen', 249000, 'Short thể thao vải thoáng khí.'],
                ['Quần short kaki be', 299000, 'Short kaki đi biển, dạo phố.'],
                ['Quần short jean xanh', 319000, 'Short jean basic.'],
                ['Quần short linen trắng', 279000, 'Short linen mát mẻ.'],
                ['Quần short jogger', 269000, 'Short jogger bo gấu.'],
                ['Quần short thể thao xanh navy', 259000, 'Short thể thao xanh navy năng động.'],
                ['Quần short kaki nâu', 309000, 'Short kaki nâu trung tính, dễ phối.'],
                ['Quần short linen be', 289000, 'Short linen be thoáng mát, tối giản.'],
            ],
            'Váy đầm' => [
                ['Váy midi hoa nhí', 459000, 'Váy midi họa tiết hoa nhỏ dễ thương.'],
                ['Đầm maxi chiffon', 599000, 'Đầm maxi bay nhẹ nhàng.'],
                ['Váy công sở đen', 529000, 'Váy bút chì công sở.'],
                ['Đầm suông linen', 489000, 'Đầm suông thoải mái.'],
                ['Váy two-piece set', 649000, 'Set váy áo đồng bộ.'],
                ['Đầm dự tiệc đỏ', 899000, 'Đầm dự tiệc sang trọng.'],
                ['Váy yếm denim', 419000, 'Yếm denim trẻ trung.'],
                ['Váy midi xếp ly be', 469000, 'Váy midi xếp ly nhẹ nhàng, thanh lịch.'],
                ['Đầm bodycon đen', 689000, 'Đầm bodycon đen ôm dáng, sang trọng.'],
                ['Đầm hoa nhí tay bồng', 579000, 'Đầm hoa nhí tay bồng nữ tính.'],
            ],
            'Áo len & Hoodie' => [
                ['Hoodie basic đen', 449000, 'Hoodie nỉ ấm, form unisex.'],
                ['Hoodie zip xám', 499000, 'Hoodie khóa kéo tiện lợi.'],
                ['Áo len cổ lọ', 389000, 'Len mỏng mặc layer.'],
                ['Sweatshirt oversize', 419000, 'Sweatshirt form rộng.'],
                ['Áo len dày cable knit', 559000, 'Len dệt kiểu cable ấm.'],
                ['Hoodie in logo', 469000, 'Hoodie in logo thương hiệu.'],
                ['Hoodie basic kem', 459000, 'Hoodie kem sáng nhẹ nhàng, dễ phối.'],
                ['Hoodie zip xám bạc', 519000, 'Hoodie zip xám bạc hiện đại.'],
                ['Áo len cổ lọ nâu', 399000, 'Áo len cổ lọ tông nâu ấm áp.'],
            ],
            'Giày dép' => [
                ['Giày sneaker trắng basic', 699000, 'Sneaker trắng dễ phối.'],
                ['Giày sneaker đen chunky', 799000, 'Sneaker đế dày thời trang.'],
                ['Sandal da nâu', 399000, 'Sandal da nam.'],
                ['Dép lê quai ngang', 199000, 'Dép lê đi trong nhà và dạo phố.'],
                ['Boot cổ thấp đen', 999000, 'Boot da PU cổ thấp.'],
                ['Giày canvas xanh', 449000, 'Giày vải canvas classic.'],
                ['Giày thể thao chạy bộ', 849000, 'Giày chạy bộ đệm êm.'],
                ['Giày sneaker be', 729000, 'Sneaker be tối giản, thanh lịch.'],
                ['Sandal quai chéo đen', 429000, 'Sandal quai chéo đen chắc chắn.'],
                ['Boot cao cổ nâu', 1049000, 'Boot cao cổ nâu phong cách.'],
            ],
            'Túi xách' => [
                ['Túi tote canvas', 299000, 'Túi tote vải bền.'],
                ['Balo thời trang đen', 549000, 'Balo đi học, đi làm.'],
                ['Túi đeo chéo mini', 379000, 'Túi mini đựng phone, ví.'],
                ['Clutch dự tiệc', 459000, 'Clutch sang cho sự kiện.'],
                ['Túi bucket da PU', 499000, 'Túi bucket trendy.'],
                ['Balo laptop chống nước', 629000, 'Balo đựng laptop 15 inch.'],
                ['Túi tote canvas be', 319000, 'Túi tote be tối giản, dễ mang.'],
                ['Balo mini thời trang', 569000, 'Balo mini nhỏ gọn, tiện dụng.'],
                ['Clutch ánh bạc', 489000, 'Clutch ánh bạc nổi bật cho tiệc tối.'],
            ],
            'Phụ kiện' => [
                ['Mũ bucket vàng', 159000, 'Mũ bucket chống nắng.'],
                ['Thắt lưng da nâu', 249000, 'Thắt lưng da PU classic.'],
                ['Kính mát gọng tròn', 199000, 'Kính mát UV400.'],
                ['Khăn choàng len', 179000, 'Khăn len ấm mùa đông.'],
                ['Vòng tay hợp kim', 129000, 'Phụ kiện nữ tinh tế.'],
                ['Mũ lưỡi trai đen', 149000, 'Cap basic unisex.'],
                ['Mũ bucket be', 169000, 'Mũ bucket be nhẹ nhàng, thời trang.'],
                ['Kính mát gọng vuông đen', 209000, 'Kính mát gọng vuông đen cá tính.'],
                ['Thắt lưng da đen basic', 259000, 'Thắt lưng da đen tối giản.'],
            ],
        ];

        $allProducts = [];
        $index = 1;

        foreach ($productsByCategory as $categoryName => $products) {
            $category = Category::where('name', $categoryName)->first();
            if (!$category) {
                continue;
            }

            foreach ($products as [$name, $price, $description]) {
                $allProducts[] = compact('category', 'categoryName', 'name', 'price', 'description', 'index');
                $index++;
            }
        }

        $total = count($allProducts);
        $featuredTarget = (int) round($total * 0.62);
        $featuredIndexes = array_slice(
            collect(range(0, $total - 1))->shuffle()->all(),
            0,
            $featuredTarget
        );

        foreach ($allProducts as $i => $item) {
            $hasDiscount = $i % 2 === 0;
            $discount = $hasDiscount ? $discountOptions[array_rand($discountOptions)] : 0;
            $isFeatured = in_array($i, $featuredIndexes);
            $image = ProductImageHelper::exactUrlForProductName($item['name'], $item['index']);

            if (!$image) {
                throw new \RuntimeException('Thiếu ảnh khớp theo tên sản phẩm: ' . $item['name']);
            }

            Product::create([
                'category_id' => $item['category']->id,
                'name' => $item['name'],
                'slug' => Str::slug($item['name']) . '-' . $item['index'],
                'description' => $item['description'],
                'price' => $item['price'],
                'discount_percent' => $discount,
                'stock' => rand(20, 150),
                'available_sizes' => $sizesByCategory[$item['categoryName']] ?? ['S', 'M', 'L', 'XL'],
                'available_colors' => $colorsByCategory[$item['categoryName']] ?? ['Đen', 'Trắng', 'Xám'],
                'is_featured' => $isFeatured,
                'status' => true,
                'image' => $image,
            ]);
        }
    }
}
