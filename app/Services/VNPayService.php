<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;

class VNPayService
{
    private string $vnp_TmnCode;
    private string $vnp_HashSecret;
    private string $vnp_Url;
    private string $vnp_ReturnUrl;

    public function __construct()
    {
        $this->vnp_TmnCode = Config::get('services.vnpay.vnp_TmnCode', '');
        $this->vnp_HashSecret = Config::get('services.vnpay.vnp_HashSecret', '');
        $this->vnp_Url = Config::get('services.vnpay.vnp_Url', '');
        $this->vnp_ReturnUrl = Config::get('services.vnpay.vnp_ReturnUrl', '');
    }

    /**
     * Tạo URL thanh toán VNPay
     */
    public function createPaymentUrl(int $orderId, float $amount, string $orderInfo): string
    {
        $vnp_TxnRef = $orderId;
        $vnp_OrderInfo = $orderInfo;
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $amount * 100; // VNPay yêu cầu nhân 100
        $vnp_Locale = 'vn';
        $vnp_CreateDate = date('YmdHis');
        $vnp_IpAddr = request()->ip();

        $inputData = [
            'vnp_Version' => '2.1.0',
            'vnp_Command' => 'pay',
            'vnp_TmnCode' => $this->vnp_TmnCode,
            'vnp_Locale' => $vnp_Locale,
            'vnp_CurrCode' => 'VND',
            'vnp_TxnRef' => $vnp_TxnRef,
            'vnp_OrderInfo' => $vnp_OrderInfo,
            'vnp_OrderType' => $vnp_OrderType,
            'vnp_Amount' => $vnp_Amount,
            'vnp_ReturnUrl' => $this->vnp_ReturnUrl,
            'vnp_IpAddr' => $vnp_IpAddr,
            'vnp_CreateDate' => $vnp_CreateDate,
            'vnp_BankCode' => '',
        ];

        ksort($inputData);
        $query = http_build_query($inputData);
        $vnp_HashSecret = $this->vnp_HashSecret;
        $vnpSecureHash = hash_hmac('sha512', $query, $vnp_HashSecret);

        $paymentUrl = $this->vnp_Url . '?' . $query . '&vnp_SecureHash=' . $vnpSecureHash;

        return $paymentUrl;
    }

    /**
     * Xác thực kết quả thanh toán từ VNPay
     */
    public function verifyReturnUrl(array $queryParams): array
    {
        $vnp_SecureHash = $queryParams['vnp_SecureHash'] ?? '';

        // Loại bỏ hash và hash type khỏi dữ liệu
        unset($queryParams['vnp_SecureHash']);
        unset($queryParams['vnp_SecureHashType']);

        ksort($queryParams);
        $query = http_build_query($queryParams);
        $vnp_HashSecret = $this->vnp_HashSecret;
        $verifyHash = hash_hmac('sha512', $query, $vnp_HashSecret);

        $isValid = $verifyHash === $vnp_SecureHash;

        return [
            'is_valid' => $isValid,
            'response_code' => $queryParams['vnp_ResponseCode'] ?? '',
            'transaction_no' => $queryParams['vnp_TransactionNo'] ?? '',
            'txn_ref' => $queryParams['vnp_TxnRef'] ?? '',
            'amount' => ($queryParams['vnp_Amount'] ?? 0) / 100,
            'bank_code' => $queryParams['vnp_BankCode'] ?? '',
            'order_info' => $queryParams['vnp_OrderInfo'] ?? '',
            'is_success' => $isValid && ($queryParams['vnp_ResponseCode'] === '00'),
        ];
    }
}
