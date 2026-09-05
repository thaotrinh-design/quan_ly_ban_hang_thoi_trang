<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;

class VietQRService
{
    private string $bankBin;
    private string $accountNumber;
    private string $accountHolder;
    private string $accountName;

    public function __construct()
    {
        $this->bankBin = Config::get('vietqr.bank_bin', '970436');
        $this->accountNumber = Config::get('vietqr.account_number', '1023456789');
        $this->accountHolder = Config::get('vietqr.account_holder', 'TINA STORE');
        $this->accountName = Config::get('vietqr.account_name', 'TINA STORE');
    }

    /**
     * Tạo QR code URL cho đơn hàng
     */
    public function generateQRUrl(float $amount, string $purpose): string
    {
        $encodedPurpose = urlencode($purpose);
        return "https://img.vietqr.io/image/{$this->bankBin}-{$this->accountNumber}-compact2.png?amount={$amount}&addInfo={$encodedPurpose}&accountName={$this->accountName}";
    }

    /**
     * Tạo thông tin hiển thị QR
     */
    public function getQRInfo(float $amount, string $purpose): array
    {
        return [
            'bank_name' => 'Vietcombank',
            'bank_bin' => $this->bankBin,
            'account_number' => $this->accountNumber,
            'account_holder' => $this->accountHolder,
            'amount' => $amount,
            'purpose' => $purpose,
            'qr_url' => $this->generateQRUrl($amount, $purpose),
        ];
    }
}
