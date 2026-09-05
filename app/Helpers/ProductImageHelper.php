<?php

namespace App\Helpers;

class ProductImageHelper
{
    private static array $exactNameRules = [
        'áo thun basic cotton đen' => 'https://images.pexels.com/photos/34156907/pexels-photo-34156907.jpeg?_gl=1*8j8b74*_ga*MTUyNzg5OTc4NC4xNzgxMDcwMjg5*_ga_8JE65Q40S6*czE3ODEwNzAyODgkbzEkZzEkdDE3ODEwNzA4NjkkajU3JGwwJGgw',
        'áo thun oversize trắng' => 'photo-1576566588028-4147f3842f27',
        'áo thun in graphic vintage' => 'photo-1583743814966-8936f5b7be1a',
        'áo thun polo nam navy' => 'https://media.routine.vn/1200x1500/prod/variant/navy-1-webp-xts9.webp',
        'áo thun tay dài basic' => 'photo-1521572163474-6864f9cf17ab',
        'áo thun nam phối viền' => 'photo-1521572163474-6864f9cf17ab',
        'áo thun nam dry-fit thể thao' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR_YQJd5pwgLSnoj1nHz5EoV6xZ85zRXaUdeg&s',
        'áo sơ mi trắng công sở' => 'photo-1596755094514-f87e34085b2c',
        'áo sơ mi kẻ caro xanh' => 'photo-1602810318383-e386cc2a3ccf',
        'áo sơ mi linen be' => 'photo-1622445275463-afa2ab738c34',
        'áo sơ mi dài tay đen' => 'photo-1596755094514-f87e34085b2c',
        'áo sơ mi họa tiết hoa' => 'https://4menshop.com/images/thumbs/2022/03/ao-so-mi-hoa-tiet-bong-asm063-16115.JPG',
        'áo sơ mi denim' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT2WouYEZRYxdiXwR72Ka5VHRIwqBHxYV38Vg&s',
        'áo khoác denim xanh' => 'photo-1544022613-e87ca75a784a',
        'áo khoác bomber đen' => 'photo-1551028719-00167b16eac5',
        'áo blazer nữ be' => 'photo-1591047139829-d91aecb6caea',
        'áo khoác gió chống nước' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRCzolfdCF0jlvnsJ9rwgofc1AORW4F4eB68Q&s',
        'áo khoác da pu' => 'https://lados.vn/wp-content/uploads/2024/11/1-den-ld2118.jpg',
        'áo cardigan len' => 'photo-1434389677669-e08b4cac3105',
        'quần jean slim fit xanh đậm' => 'photo-1541099649105-f69ad21f3246',
        'quần jean baggy nữ' => 'photo-1541099649105-f69ad21f3246',
        'quần jean ống rộng' => 'https://bizweb.dktcdn.net/thumb/1024x1024/100/422/076/products/o1cn0143dvqy1wyunstlg0b-2217573442801-0-cib-1.jpg?v=1731829273413',
        'quần jean rách gối' => 'photo-1542272604-787c3835535d',
        'quần jean đen basic' => 'photo-1542272604-787c3835535d',
        'quần jean short nữ' => 'photo-1591195853828-11db59a44f6b',
        'quần short thể thao đen' => 'photo-1506629082955-511b1aa562c8',
        'quần short kaki be' => 'https://cdn.vuahanghieu.com/unsafe/0x900/left/top/smart/filters:quality(90)/https://admin.vuahanghieu.com/upload/product/2024/12/quan-short-kaki-nam-calvin-klein-ck-shorts-40p613230-mau-be-size-30-6757c0ceea688-10122024111718.jpg',
        'quần short jean xanh' => 'photo-1591195853828-11db59a44f6b',
        'quần short linen trắng' => 'photo-1591195853828-11db59a44f6b',
        'quần short jogger' => 'https://down-vn.img.susercontent.com/file/sg-11134201-822wp-mo4xv505m876ee',
        'váy midi hoa nhí' => 'photo-1595777457583-95e059d581b8',
        'đầm maxi chiffon' => 'photo-1515372039744-b8f02a3ae446',
        'váy công sở đen' => 'photo-1566174053879-31528523f8ae',
        'đầm suông linen' => 'photo-1595777457583-95e059d581b8',
        'váy two-piece set' => 'photo-1496747611176-843222e1e57c',
        'đầm dự tiệc đỏ' => 'photo-1566174053879-31528523f8ae',
        'váy yếm denim' => 'photo-1515372039744-b8f02a3ae446',
        'hoodie basic đen' => 'photo-1556821840-3a63f95609a7',
        'hoodie zip xám' => 'photo-1620799140408-edc6dcb6d633',
        'áo len cổ lọ' => 'https://sakurafashion.vn/upload/sanpham/large/27020-ao-len-co-lo-nu-hoa-tiet-van-thung-phong-cach-retro-1.jpg',
        'sweatshirt oversize' => 'https://media.routine.vn/1200x1500/prod/variant/19f25swe002-gray-1-jpg-8ktr.webp',
        'áo len dày cable knit' => 'photo-1434389677669-e08b4cac3105',
        'hoodie in logo' => 'photo-1556821840-3a63f95609a7',
        'giày sneaker trắng basic' => 'photo-1549298916-b41d501d3772',
        'giày sneaker đen chunky' => 'https://supersports.com.vn/cdn/shop/files/6006060-001-1_1200x1200.jpg?v=1771835471',
        'sandal da nâu' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS26bgcSGU6x18dNLcRVxEWazGX9FS4PeH-MA&s',
        'dép lê quai ngang' => 'https://bizweb.dktcdn.net/100/059/374/products/2-cdc66385-c73a-452b-9383-29872c3991d4.jpg?v=1744687128713',
        'boot cổ thấp đen' => 'photo-1608256246200-53e635b5b65f',
        'giày canvas xanh' => 'photo-1460353581641-37baddab0fa2',
        'giày thể thao chạy bộ' => 'https://down-vn.img.susercontent.com/file/vn-11134207-7ras8-m2dmpmtnaawk0c',
        'túi tote canvas' => 'photo-1584917865442-de89df76afd3',
        'balo thời trang đen' => 'photo-1548036328-c9fa89d128fa',
        'túi đeo chéo mini' => 'photo-1590874103328-eac38a683ce7',
        'clutch dự tiệc' => 'photo-1590874103328-eac38a683ce7',
        'túi bucket da pu' => 'photo-1548036328-c9fa89d128fa',
        'balo laptop chống nước' => 'photo-1553062407-98eeb64c6a62',
        'mũ bucket vàng' => 'photo-1521369909029-2afed882baee',
        'mũ bucket be' => 'photo-1521369909029-2afed882baee',
        'thắt lưng da nâu' => 'https://product.hstatic.net/1000111569/product/dnn001_nau_dam_4071c280cc8941e88406207ed42c85bb_1024x1024.jpg',
        'thắt lưng da đen basic' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSMB06xdgTapvf3WmV59UkA1BgZ4G84dFDc9g&s',
        'kính mát gọng tròn' => 'https://kavi.vn/upload/image/06(17).jpg',
        'kính mát gọng vuông đen' => 'photo-1572635196237-14b250f65317',
        'khăn choàng len' => 'https://sakurafashion.vn/upload/sanpham/large/72709-khan-choang-co-len-nu-3.jpg',
        'vòng tay hợp kim' => 'https://cdn.pnj.io/images/detailed/130/tv0000x000002-vong-tay-style-by-pnj-01.png',
        'mũ lưỡi trai đen' => 'https://img.lazcdn.com/g/p/95d6594976ce14cd2c20b78bfa3378e2.jpg_720x720q80.jpg',
        'áo thun slim fit be' => 'photo-1521572163474-6864f9cf17ab',
        'áo thun oversize xanh pastel' => 'photo-1576566588028-4147f3842f27',
        'áo thun in slogan đen' => 'photo-1583743814966-8936f5b7be1a',
        'áo sơ mi sọc xanh' => 'photo-1602810318383-e386cc2a3ccf',
        'áo sơ mi lụa trắng' => 'photo-1596755094514-f87e34085b2c',
        'áo sơ mi denim wash' => 'photo-1598033129188-e4ae8f12af4a',
        'áo khoác bomber xám' => 'photo-1551028719-00167b16eac5',
        'áo khoác denim xanh nhạt' => 'photo-1544022613-e87ca75a784a',
        'áo blazer đen basic' => 'photo-1591047139829-d91aecb6caea',
        'quần jean baggy rách gối' => 'photo-1541099649105-f69ad21f3246',
        'quần jean ống rộng be' => 'photo-1475178626620-a4d074967a6c',
        'quần jean đen basic slim' => 'photo-1542272604-787c3835535d',
        'quần short thể thao xanh navy' => 'photo-1506629082955-511b1aa562c8',
        'quần short kaki nâu' => 'photo-1624378515194-6bb89565a0b9',
        'quần short linen be' => 'photo-1591195853828-11db59a44f6b',
        'váy midi xếp ly be' => 'photo-1595777457583-95e059d581b8',
        'đầm bodycon đen' => 'photo-1566174053879-31528523f8ae',
        'đầm hoa nhí tay bồng' => 'photo-1595777457583-95e059d581b8',
        'hoodie basic kem' => 'photo-1556821840-3a63f95609a7',
        'hoodie zip xám bạc' => 'photo-1620799140408-edc6dcb6d633',
        'áo len cổ lọ nâu' => 'photo-1578587018452-b89307b3952b',
        'giày sneaker be' => 'photo-1549298916-b41d501d3772',
        'sandal quai chéo đen' => 'photo-1603487746851-032b1f5b4d9f',
        'boot cao cổ nâu' => 'photo-1608256246200-53e635b5b65f',
        'túi tote canvas be' => 'photo-1584917865442-de89df76afd3',
        'balo mini thời trang' => 'photo-1548036328-c9fa89d128fa',
        'clutch ánh bạc' => 'photo-1590874103328-eac38a683ce7',
    ];

    private static array $nameRules = [
        ['polo', 'photo-1621070277009-6746cc1f6012'],
        ['oversize', 'photo-1576566588028-4147f3842f27'],
        ['graphic', 'photo-1583743814966-8936f5b7be1a'],
        ['dry-fit', 'photo-1618354691373-d8516515c3d9'],
        ['thun tay dài', 'photo-1521572163474-6864f9cf17ab'],
        ['thun', 'photo-1521572163474-6864f9cf17ab'],
        ['sơ mi trắng', 'photo-1596755094514-f87e34085b2c'],
        ['sơ mi kẻ', 'photo-1602810318383-e386cc2a3ccf'],
        ['sơ mi linen', 'photo-1622445275463-afa2ab738c34'],
        ['sơ mi denim', 'photo-1598033129188-e4ae8f12af4a'],
        ['sơ mi hoa', 'photo-1598032890315-3cd4c1e2e8b0'],
        ['sơ mi', 'photo-1596755094514-f87e34085b2c'],
        ['bomber', 'photo-1551028719-00167b16eac5'],
        ['blazer', 'photo-1591047139829-d91aecb6caea'],
        ['denim xanh', 'photo-1544022613-e87ca75a784a'],
        ['khoác gió', 'photo-1544966503-d8af6d1d3b97'],
        ['khoác da', 'photo-1521220290189-305b07af8740'],
        ['cardigan', 'photo-1434389677669-e08b4cac3105'],
        ['khoác', 'photo-1544022613-e87ca75a784a'],
        ['baggy', 'photo-1541099649105-f69ad21f3246'],
        ['ống rộng', 'photo-1475178626620-a4d074967a6c'],
        ['rách gối', 'photo-1542272604-787c3835535d'],
        ['jean short', 'photo-1591195853828-11db59a44f6b'],
        ['jean đen', 'photo-1542272604-787c3835535d'],
        ['jean', 'photo-1541099649105-f69ad21f3246'],
        ['short thể thao', 'photo-1506629082955-511b1aa562c8'],
        ['short kaki', 'photo-1624378515194-6bb89565a0b9'],
        ['short linen', 'photo-1591195853828-11db59a44f6b'],
        ['short jogger', 'photo-1624378515194-6bb89565a0b9'],
        ['short', 'photo-1591195853828-11db59a44f6b'],
        ['maxi', 'photo-1515372039744-b8f02a3ae446'],
        ['midi hoa', 'photo-1595777457583-95e059d581b8'],
        ['dự tiệc', 'photo-1566174053879-31528523f8ae'],
        ['two-piece', 'photo-1496747611176-843222e1e57c'],
        ['yếm', 'photo-1515372039744-b8f02a3ae446'],
        ['váy', 'photo-1595777457583-95e059d581b8'],
        ['đầm', 'photo-1566174053879-31528523f8ae'],
        ['hoodie zip', 'photo-1620799140408-edc6dcb6d633'],
        ['hoodie', 'photo-1556821840-3a63f95609a7'],
        ['sweatshirt', 'photo-1578587018452-b89307b3952b'],
        ['cable knit', 'photo-1434389677669-e08b4cac3105'],
        ['len cổ lọ', 'photo-1578587018452-b89307b3952b'],
        ['len', 'photo-1578587018452-b89307b3952b'],
        ['sneaker trắng', 'photo-1549298916-b41d501d3772'],
        ['sneaker đen', 'photo-1606107557195-0a29cb0701f2'],
        ['sneaker', 'photo-1549298916-b41d501d3772'],
        ['sandal', 'photo-1603487746851-032b1f5b4d9f'],
        ['dép lê', 'photo-1603487746851-032b1f5b4d9f'],
        ['boot', 'photo-1608256246200-53e635b5b65f'],
        ['canvas', 'photo-1460353581641-37baddab0fa2'],
        ['chạy bộ', 'photo-1606107557195-0a29cb0701f2'],
        ['giày', 'photo-1549298916-b41d501d3772'],
        ['tote', 'photo-1584917865442-de89df76afd3'],
        ['balo laptop', 'photo-1553062407-98eeb64c6a62'],
        ['balo', 'photo-1548036328-c9fa89d128fa'],
        ['clutch', 'photo-1590874103328-eac38a683ce7'],
        ['bucket', 'photo-1548036328-c9fa89d128fa'],
        ['túi đeo chéo', 'photo-1590874103328-eac38a683ce7'],
        ['túi', 'photo-1584917865442-de89df76afd3'],
        ['mũ bucket', 'photo-1521369909029-2afed882baee'],
        ['mũ lưỡi trai', 'photo-1575428652377-a2d80a067ee9'],
        ['mũ', 'photo-1521369909029-2afed882baee'],
        ['thắt lưng', 'photo-1624222203573-0a8916dc8176'],
        ['kính mát', 'photo-1572635196237-14b250f65317'],
        ['khăn choàng', 'photo-1520903929203-3882950f7f38'],
        ['vòng tay', 'photo-1611595431811-afef7041d0d1'],
    ];

    private static array $categoryFallback = [
        'Áo thun nam' => 'photo-1521572163474-6864f9cf17ab',
        'Áo sơ mi' => 'photo-1596755094514-f87e34085b2c',
        'Áo khoác' => 'photo-1551028719-00167b16eac5',
        'Quần jean' => 'photo-1541099649105-f69ad21f3246',
        'Quần short' => 'photo-1591195853828-11db59a44f6b',
        'Váy đầm' => 'photo-1595777457583-95e059d581b8',
        'Áo len & Hoodie' => 'photo-1556821840-3a63f95609a7',
        'Giày dép' => 'photo-1549298916-b41d501d3772',
        'Túi xách' => 'photo-1584917865442-de89df76afd3',
        'Phụ kiện' => 'photo-1521369909029-2afed882baee',
    ];

    public static function urlForProduct(string $name, ?string $categoryName, int $productId): string
    {
        $nameLower = mb_strtolower($name);

        $exactKey = self::normalizeName($nameLower);
        $exactUrl = self::exactUrlForProductName($exactKey, $productId);
        if ($exactUrl) {
            return $exactUrl;
        }

        foreach (self::$nameRules as [$keyword, $photoId]) {
            if (str_contains($nameLower, $keyword)) {
                return self::buildUrl($photoId, $productId);
            }
        }

        $fallback = self::$categoryFallback[$categoryName] ?? 'photo-1483985988355-763728e1935b';

        return self::buildUrl($fallback, $productId);
    }

    public static function url(?string $categoryName, int $productId): string
    {
        $fallback = self::$categoryFallback[$categoryName] ?? 'photo-1483985988355-763728e1935b';

        return self::buildUrl($fallback, $productId);
    }

    public static function exactUrlForProductName(string $name, int $productId): ?string
{
    $exactKey = self::normalizeName(mb_strtolower($name));

    if (!isset(self::$exactNameRules[$exactKey])) {
        return null;
    }

    $value = self::$exactNameRules[$exactKey];

    // Nếu là URL ảnh đầy đủ
    if (
        str_starts_with($value, 'http://') ||
        str_starts_with($value, 'https://')
    ) {
        return $value;
    }

    // Nếu là photo-id của Unsplash
    return self::buildUrl($value, $productId);
}
    private static function buildUrl(string $photoId, int $seed): string
{
    // Nếu đã là URL hoàn chỉnh
    if (
        str_starts_with($photoId, 'http://') ||
        str_starts_with($photoId, 'https://')
    ) {
        return $photoId;
    }

    return "https://images.unsplash.com/{$photoId}?w=450&h=560&fit=crop&q=85&sig={$seed}";
}

    private static function normalizeName(string $value): string
    {
        return trim(preg_replace('/\s+/u', ' ', $value));
    }
}
