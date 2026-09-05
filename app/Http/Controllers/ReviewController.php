<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'star' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        if (!auth()->user()->hasPurchasedProduct($request->product_id)) {
            return back()->with('error', 'Chỉ khách hàng đã mua sản phẩm mới được đánh giá.');
        }

        Review::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'product_id' => $request->product_id,
            ],
            [
                'star' => $request->star,
                'comment' => $request->comment,
            ]
        );

        return back()->with('success', 'Đánh giá sản phẩm thành công.');
    }
}
