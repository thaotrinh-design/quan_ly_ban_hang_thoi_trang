<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\VNPayService;
use App\Services\VietQRService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Hiển thị trang QR VietQR cho đơn hàng
     */
    public function vietqr(Order $order)
    {
        // Kiểm tra quyền
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $vietqrService = new VietQRService();
        $purpose = 'TINA STORE - DH' . $order->id;
        $qrInfo = $vietqrService->getQRInfo($order->total, $purpose);

        return view('payment.vietqr', compact('order', 'qrInfo'));
    }

    /**
     * Redirect đến VNPay để thanh toán
     */
    public function vnpayPayment(Order $order)
    {
        // Kiểm tra quyền
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Kiểm tra trạng thái đơn
        if ($order->isPaid()) {
            return redirect()->route('orders.show', $order->id)
                ->with('error', 'Đơn hàng đã được thanh toán');
        }

        $vnpayService = new VNPayService();
        $orderInfo = 'Thanh toan don hang #' . $order->id;
        $paymentUrl = $vnpayService->createPaymentUrl($order->id, $order->total, $orderInfo);

        return redirect($paymentUrl);
    }

    /**
     * Callback từ VNPay (Return URL)
     */
    public function vnpayCallback(Request $request)
    {
        $vnpayService = new VNPayService();
        $result = $vnpayService->verifyReturnUrl($request->all());

        if ($result['is_valid'] && $result['is_success']) {
            $order = Order::find($result['txn_ref']);

            if ($order) {
                DB::transaction(function () use ($order, $result) {
                    $order->update([
                        'vnpay_txn_ref' => $result['txn_ref'],
                        'vnpay_transaction_no' => $result['transaction_no'],
                        'vnpay_response_code' => $result['response_code'],
                        'vnpay_bank_code' => $result['bank_code'],
                        'paid_at' => now(),
                    ]);

                    $order->logStatusChange('completed', 'Thanh toán VNPay thành công - Mã GD: ' . $result['transaction_no'], 'VNPay');
                });

                return redirect()->route('payment.result', [
                    'order' => $order->id,
                    'status' => 'success',
                    'message' => 'Thanh toán thành công!',
                ]);
            }
        }

        // Thanh toán thất bại hoặc order không hợp lệ
        $orderId = $result['txn_ref'] ?? null;
        return redirect()->route('payment.result', [
            'order' => $orderId,
            'status' => 'failed',
            'message' => 'Thanh toán thất bại hoặc bị hủy.',
        ]);
    }

    /**
     * Trang kết quả thanh toán
     */
    public function paymentResult(Request $request)
    {
        $status = $request->get('status', 'failed');
        $message = $request->get('message', '');
        $orderId = $request->get('order');

        $order = $orderId ? Order::find($orderId) : null;

        if ($order && $order->user_id !== Auth::id()) {
            $order = null;
        }

        return view('payment.result', compact('order', 'status', 'message'));
    }
}
