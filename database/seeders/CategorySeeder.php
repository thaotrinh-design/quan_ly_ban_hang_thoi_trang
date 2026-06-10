<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Áo thun nam' => 'Áo thun nam basic, oversize, in họa tiết',
            'Áo sơ mi' => 'Áo sơ mi công sở và dạo phố',
            'Áo khoác' => 'Áo khoác denim, bomber, blazer',
            'Quần jean' => 'Quần jean nam nữ các kiểu',
            'Quần short' => 'Quần short thể thao và thời trang',
            'Váy đầm' => 'Váy midi, maxi, công sở',
            'Áo len & Hoodie' => 'Áo len, hoodie, sweatshirt',
            'Giày dép' => 'Giày sneaker, sandal, boot',
            'Túi xách' => 'Túi tote, balo, clutch',
            'Phụ kiện' => 'Mũ, thắt lưng, kính mắt',
        ];

        foreach ($categories as $name => $description) {
            Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => $description,
                'status' => true,
            ]);
        }
    }
}
