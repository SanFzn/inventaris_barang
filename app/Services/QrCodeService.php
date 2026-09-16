<?php

namespace App\Services;

class QrCodeService
{
    const QR_API_URL = 'https://api.qrserver.com/v1/create-qr-code/';
    const QR_SIZE = 200;
    const CACHE_DURATION = 31536000; // 1 tahun

    /**
     * Generate QR code image dari kode/text
     * 
     * @param string $code Kode atau text yang akan di-encode ke QR
     * @return string QR code image binary
     */
    public function generateQrImage(string $code): string
    {
        $qrUrl = self::QR_API_URL . '?size=' . self::QR_SIZE . '&data=' . urlencode($code);
        
        return file_get_contents($qrUrl);
    }
}
