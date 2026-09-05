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
        $encodedName = urlencode($this->accountName);
        return "https://img.vietqr.io/image/{$this->bankBin}-{$this->accountNumber}-compact2.png?amount={$amount}&addInfo={$encodedPurpose}&accountName={$encodedName}";
    }

    /**
     * Tạo thông tin hiển thị QR
     */
    public function getQRInfo(float $amount, string $purpose): array
    {
        $bankNames = [
            '970436' => 'Vietcombank',
            '970418' => 'BIDV',
            '970405' => 'VietinBank',
            '970407' => 'Techcombank',
            '970422' => 'MB Bank',
            '970423' => 'ACB',
            '970426' => 'Sacombank',
            '970432' => 'VPBank',
            '970433' => 'VPBank',
            '970437' => 'HDBank',
            '970441' => 'VIB',
        ];

        return [
            'bank_name' => $bankNames[$this->bankBin] ?? 'Ngân hàng',
            'bank_bin' => $this->bankBin,
            'account_number' => $this->accountNumber,
            'account_holder' => $this->accountHolder,
            'amount' => $amount,
            'purpose' => $purpose,
            'qr_url' => $this->generateQRUrl($amount, $purpose),
        ];
    }
}
