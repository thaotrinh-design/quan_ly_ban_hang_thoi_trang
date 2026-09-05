<?php

namespace App\Services;

use App\Models\Category;
use App\Models\ChatMessage;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Str;

class ChatService
{
    public function reply(string $sessionId, string $message, ?int $userId = null): string
    {
        ChatMessage::create([
            'session_id' => $sessionId,
            'user_id' => $userId,
            'sender' => 'user',
            'message' => $message,
        ]);

        $reply = $this->generateReply($message, $userId);

        ChatMessage::create([
            'session_id' => $sessionId,
            'user_id' => $userId,
            'sender' => 'bot',
            'message' => $reply,
        ]);

        return $reply;
    }

    public function getHistory(string $sessionId): array
    {
        return ChatMessage::where('session_id', $sessionId)
            ->orderBy('id')
            ->get()
            ->map(fn ($m) => [
                'sender' => $m->sender,
                'message' => $m->message,
                'time' => $m->created_at->format('H:i'),
            ])
            ->toArray();
    }

    private function generateReply(string $message, ?int $userId): string
    {
        $text = Str::lower(trim($message));
        $store = config('store') ?? [];

        $productReply = $this->searchProductsAdvice($text);
        if ($productReply) {
            return $productReply;
        }

        if ($this->matches($text, ['xin chào', 'chào', 'hello', 'hi', 'hey'])) {
            return 'Xin chào! Tôi là trợ lý AI TINA STORE. Hỏi tôi tên sản phẩm (vd: áo thun, quần jean), danh mục, giá, size/màu — tôi sẽ tư vấn dựa trên kho hàng thực tế ạ!';
        }

        if ($this->matches($text, ['đơn hàng', 'order', 'theo dõi', 'trạng thái'])) {
            if ($userId) {
                $latest = Order::where('user_id', $userId)->orderByDesc('id')->first();
                if ($latest) {
                    $status = $this->translateStatus($latest->status);
                    $type = $latest->order_type === 'pickup' ? 'Mua tại chỗ' : 'Giao hàng';
                    return "Đơn #{$latest->id} ({$type}) - {$status}. Tạm tính: " . number_format($latest->getSubtotalAmount()) . "đ" .
                        ($latest->hasDiscount() ? ", giảm " . number_format($latest->discount_amount) . "đ, còn " : ", thanh toán ") .
                        number_format($latest->total) . "đ.";
                }
            }
            return 'Đăng nhập và vào mục Đơn hàng để theo dõi. Hotline: ' . ($store['phone'] ?? '');
        }

        if ($this->matches($text, ['giảm giá', 'voucher', 'mã', 'coupon', 'khuyến mãi'])) {
            return 'Mã hiện có: WELCOME10 (10%), SUMMER20 (20%), VIP30 (30%). Nhập tại bước thanh toán — hệ thống hiển thị giá trước và sau voucher.';
        }

        if ($this->matches($text, ['mua tại chỗ', 'tại chỗ', 'cửa hàng', 'đến lấy'])) {
            return "Mua tại chỗ tại {$store['address'] ?? ''}. Chọn \"Mua tại chỗ\" khi thanh toán, thanh toán COD tại quầy hoặc chuyển khoản (có mã QR). Giờ mở cửa: {$store['hours'] ?? ''}.";
        }

        if ($this->matches($text, ['đổi trả', 'hoàn tiền', 'trả hàng', 'đổi size'])) {
            return 'Đổi trả trong 7 ngày, sản phẩm còn tem. Mang hóa đơn đến ' . ($store['address'] ?? '') . ' hoặc gọi ' . ($store['phone'] ?? '') . '.';
        }

        if ($this->matches($text, ['giao hàng', 'ship', 'vận chuyển', 'bao lâu', 'mấy ngày', 'thời gian giao', 'giao về', 'địa chỉ'])) {
            return $this->getDeliveryEstimate($text, $userId);
        }

        if ($this->matches($text, ['liên hệ', 'địa chỉ', 'hotline', 'facebook', 'tiktok', 'map', 'bản đồ'])) {
            return "📍 " . ($store['address'] ?? '') . "\n📞 " . ($store['phone'] ?? '') . "\n✉️ " . ($store['email'] ?? '') . "\n🕐 " . ($store['hours'] ?? '') . "\nXem bản đồ Google ở cuối trang \"Liên hệ chúng tôi\".";
        }

        if ($this->matches($text, ['cảm ơn', 'thanks', 'thank'])) {
            return 'Không có gì ạ! Chúc bạn mua sắm vui tại TINA STORE!';
        }

        if ($this->matches($text, ['phản hồi', 'góp ý', 'khiếu nại'])) {
            return 'Đã ghi nhận phản hồi. Bộ phận CSKH liên hệ trong 24h qua ' . ($store['phone'] ?? '') . '.';
        }

        return 'Bạn có thể hỏi tên sản phẩm cụ thể (vd: "áo hoodie giá bao nhiêu", "quần jean size L"), hoặc về giao hàng, mua tại chỗ, mã giảm giá.';
    }

    private function searchProductsAdvice(string $text): ?string
    {
        $categoryMap = [
            'áo thun' => 'Áo thun nam', 'thun' => 'Áo thun nam',
            'sơ mi' => 'Áo sơ mi', 'so mi' => 'Áo sơ mi',
            'khoác' => 'Áo khoác', 'blazer' => 'Áo khoác', 'bomber' => 'Áo khoác',
            'jean' => 'Quần jean', 'quần jean' => 'Quần jean',
            'short' => 'Quần short',
            'váy' => 'Váy đầm', 'đầm' => 'Váy đầm',
            'hoodie' => 'Áo len & Hoodie', 'len' => 'Áo len & Hoodie', 'sweatshirt' => 'Áo len & Hoodie',
            'giày' => 'Giày dép', 'sneaker' => 'Giày dép', 'dép' => 'Giày dép',
            'túi' => 'Túi xách', 'balo' => 'Túi xách',
            'phụ kiện' => 'Phụ kiện', 'mũ' => 'Phụ kiện', 'kính' => 'Phụ kiện',
        ];

        $matchedCategory = null;
        foreach ($categoryMap as $keyword => $catName) {
            if (str_contains($text, $keyword)) {
                $matchedCategory = $catName;
                break;
            }
        }

        $query = Product::active()->with('category');

        if ($matchedCategory) {
            $cat = Category::where('name', $matchedCategory)->first();
            if ($cat) {
                $query->where('category_id', $cat->id);
            }
        }

        $words = array_filter(preg_split('/\s+/', $text), fn ($w) => mb_strlen($w) >= 3);
        if (!empty($words)) {
            $query->where(function ($q) use ($words) {
                foreach ($words as $word) {
                    $escaped = str_replace(['%', '_'], ['\\%', '\\_'], $word);
                    $q->orWhere('name', 'like', "%{$escaped}%")
                        ->orWhere('description', 'like', "%{$escaped}%");
                }
            });
        }

        if ($this->matches($text, ['rẻ', 'giá thấp', 'dưới 300', 'sinh viên'])) {
            $query->orderBy('price', 'asc');
        } elseif ($this->matches($text, ['đắt', 'cao cấp', 'premium'])) {
            $query->orderBy('price', 'desc');
        }

        if (preg_match('/(\d{3,})\s*(k|nghìn|ngàn|000)?/u', $text, $m)) {
            $budget = (int) $m[1];
            if ($budget < 1000) {
                $budget *= 1000;
            }
            $query->where('price', '<=', $budget + 100000)->where('price', '>=', max(0, $budget - 150000));
        }

        $products = $query->limit(4)->get();

        if ($products->isEmpty() && $matchedCategory) {
            $cat = Category::where('name', $matchedCategory)->first();
            if ($cat) {
                $products = Product::active()->with('category')
                    ->where('category_id', $cat->id)->limit(4)->get();
            }
        }

        if ($products->isEmpty()) {
            return null;
        }

        $lines = ["Tôi tìm thấy {$products->count()} sản phẩm phù hợp:\n"];
        foreach ($products as $p) {
            $sizes = implode(', ', $p->getSizesList());
            $colors = implode(', ', $p->getColorsList());
            $lines[] = "• {$p->name}" . ($p->category ? " ({$p->category->name})" : "");
            $priceText = $p->hasDiscount()
                ? number_format($p->getSellingPrice()) . 'đ (giảm ' . $p->discount_percent . '%, gốc ' . number_format($p->price) . 'đ)'
                : number_format($p->price) . 'đ';
            $lines[] = "  Giá: {$priceText} | Size: {$sizes}";
            $lines[] = "  Màu: {$colors} | Tồn: {$p->stock}";
            if ($p->description) {
                $lines[] = '  ' . Str::limit($p->description, 80);
            }
            $lines[] = '  → Xem: /shop/product/' . $p->id;
        }

        return implode("\n", $lines);
    }

    private function matches(string $text, array $keywords): bool
    {
        foreach ($keywords as $keyword) {
            if (str_contains($text, $keyword)) {
                return true;
            }
        }

        return false;
    }

    private function translateStatus(string $status): string
    {
        return match ($status) {
            'pending' => 'Chờ xác nhận',
            'processing' => 'Đang xử lý',
            'shipping' => 'Đang giao hàng',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
            default => $status,
        };
    }

    private function getDeliveryEstimate(string $text, ?int $userId = null): string
    {
        $normalized = Str::lower($text);

        $directEstimate = $this->estimateFromLocationText($normalized);
        if ($directEstimate) {
            return $directEstimate;
        }

        if ($userId) {
            $user = \App\Models\User::with(['addresses' => function ($query) {
                $query->orderByDesc('is_default')->orderByDesc('id');
            }])->find($userId);

            $savedAddress = $user?->addresses->first()?->address;
            if ($savedAddress) {
                $savedEstimate = $this->estimateFromLocationText(Str::lower($savedAddress));
                if ($savedEstimate) {
                    return 'Theo địa chỉ đã lưu của bạn (' . $savedAddress . '), ' . $savedEstimate;
                }
            }
        }

        return 'Thời gian giao hàng thường là 1-2 ngày với miền Bắc và 2-3 ngày với miền Trung hoặc miền Nam, tùy địa chỉ nhận cụ thể. Chọn "Giao hàng" khi đặt để hệ thống xác nhận chính xác hơn.';
    }

    private function estimateFromLocationText(string $text): ?string
    {
        $northKeywords = ['miền bắc', 'mien bac', 'hà nội', 'ha noi', 'hải phòng', 'hai phong', 'quảng ninh', 'quang ninh', 'lạng sơn', 'lang son', 'bắc ninh', 'bac ninh', 'thái nguyên', 'thai nguyen', 'nam định', 'nam dinh', 'hải dương', 'hai duong', 'vĩnh phúc', 'vinh phuc'];
        $centralKeywords = ['miền trung', 'mien trung', 'đà nẵng', 'da nang', 'huế', 'hue', 'quảng nam', 'quang nam', 'quảng ngãi', 'quang ngai', 'nghệ an', 'nghe an', 'khánh hòa', 'khanh hoa', 'nha trang', 'phú yên', 'phu yen', 'thừa thiên huế', 'thua thien hue'];
        $southKeywords = ['miền nam', 'mien nam', 'tp hcm', 'tphcm', 'thành phố hồ chí minh', 'thanh pho ho chi minh', 'sài gòn', 'sai gon', 'bình dương', 'binh duong', 'đồng nai', 'dong nai', 'vũng tàu', 'vung tau', 'cần thơ', 'can tho', 'long an', 'an giang', 'tiền giang', 'tien giang', 'kiên giang', 'kien giang', 'bến tre', 'ben tre'];

        if ($this->matches($text, $northKeywords)) {
            return 'Khu vực miền Bắc thường giao trong 1-2 ngày. Chọn "Giao hàng" khi đặt và nhập địa chỉ nhận để hệ thống xác nhận chính xác hơn.';
        }

        if ($this->matches($text, $centralKeywords)) {
            return 'Khu vực miền Trung thường giao trong 2-3 ngày. Chọn "Giao hàng" khi đặt và nhập địa chỉ nhận để hệ thống xác nhận chính xác hơn.';
        }

        if ($this->matches($text, $southKeywords)) {
            return 'Khu vực miền Nam thường giao trong 2-3 ngày. Chọn "Giao hàng" khi đặt và nhập địa chỉ nhận để hệ thống xác nhận chính xác hơn.';
        }

        return null;
    }
}
