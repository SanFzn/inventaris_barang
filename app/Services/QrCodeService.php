<?php

namespace App\Services;

use App\Models\Barang;
use Illuminate\Support\Str;

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

    public function generateFor(Barang $barang): void
    {
        $kode = trim((string) $barang->kode_barang);
        if ($kode === '') {
            return;
        }

        $fileName = $this->fileName($kode);
        $filePath = public_path('qr/' . $fileName);
        $this->downloadIfMissing($kode, $filePath);

        $barang->update(['file_qr' => 'qr/' . $fileName]);
    }

    public function pathFor(string $kode): string
    {
        $filePath = public_path('qr/' . $this->fileName($kode));
        $this->downloadIfMissing($kode, $filePath);

        return $filePath;
    }

    private function fileName(string $kode): string
    {
        return Str::slug($kode ?: 'qr-code') . '-' . md5($kode ?: 'qr-code') . '.png';
    }

    private function downloadIfMissing(string $kode, string $filePath): void
    {
        if (file_exists($filePath)) {
            return;
        }

        $directory = dirname($filePath);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=1000x1000&data=' . urlencode($kode);
        $imageContents = @file_get_contents($qrUrl);
        if ($imageContents !== false) {
            file_put_contents($filePath, $imageContents);
        }
    }
}
