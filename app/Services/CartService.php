<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;

class CartService
{
    public function getOrCreateCart(User $user): Cart
    {
        return Cart::firstOrCreate(['user_id' => $user->id]);
    }

    public function addItem(User $user, int $productId, int $quantity = 1, ?string $size = null, ?string $color = null): CartItem
    {
        $product = Product::with('variants')->findOrFail($productId);

        if ($size && !in_array($size, $product->getSizesList())) {
            throw new \InvalidArgumentException('Size không hợp lệ.');
        }
        if ($color && !in_array($color, $product->getColorsList())) {
            throw new \InvalidArgumentException('Màu không hợp lệ.');
        }

        // Kiểm tra tồn kho theo variant
        $variantStock = $product->getStockByVariant($size, $color);
        if ($variantStock <= 0) {
            $variantLabel = $size && $color ? " ({$size}/{$color})" : "";
            throw new \InvalidArgumentException("Sản phẩm \"{$product->name}\"{$variantLabel} đã hết hàng.");
        }

        $cart = $this->getOrCreateCart($user);

        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->where('size', $size)
            ->where('color', $color)
            ->first();

        $currentQuantity = $item ? $item->quantity : 0;
        $totalNeeded = $currentQuantity + $quantity;

        if ($totalNeeded > $variantStock) {
            throw new \InvalidArgumentException(
                "Sản phẩm \"{$product->name}\" ({$size}/{$color}) chỉ còn {$variantStock} sản phẩm trong kho."
            );
        }

        if ($item) {
            $item->update(['quantity' => $item->quantity + $quantity]);
        } else {
            $item = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'size' => $size,
                'color' => $color,
                'quantity' => $quantity,
            ]);
        }

        return $item->refresh();
    }

    public function updateQuantity(User $user, int $itemId, int $quantity): void
    {
        $cart = $this->getOrCreateCart($user);
        $item = CartItem::where('cart_id', $cart->id)->findOrFail($itemId);

        if ($quantity <= 0) {
            $item->delete();
        } else {
            $product = $item->product;
            $variantStock = $product->getStockByVariant($item->size, $item->color);

            if ($quantity > $variantStock) {
                throw new \InvalidArgumentException(
                    "Sản phẩm \"{$product->name}\" ({$item->size}/{$item->color}) chỉ còn {$variantStock} sản phẩm trong kho."
                );
            }
            $item->update(['quantity' => $quantity]);
        }
    }

    public function removeItem(User $user, int $itemId): void
    {
        $cart = $this->getOrCreateCart($user);
        CartItem::where('cart_id', $cart->id)->where('id', $itemId)->delete();
    }

    public function getTotal(Cart $cart): float
    {
        return $cart->items->sum(function ($item) {
            if (!$item->product || $item->product->trashed()) {
                return 0;
            }
            return $item->product->getSellingPrice() * $item->quantity;
        });
    }

    public function clear(Cart $cart): void
    {
        $cart->items()->delete();
    }
}
