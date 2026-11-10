<?php

namespace App\Http\Controllers;

use App\Services\QrCodeService;

class QrController extends Controller
{
    protected QrCodeService $qrCodeService;

    public function __construct(QrCodeService $qrCodeService)
    {
        $this->qrCodeService = $qrCodeService;
    }

    /**
     * Generate QR code image
     * 
     * @param string $code Kode barang/QR yang akan di-generate
     * @return \Illuminate\Http\Response QR code image
     */
    public function generate(string $code)
    {
        $qrImage = $this->qrCodeService->generateQrImage($code);

        return response($qrImage, 200)
            ->header('Content-Type', 'image/png')
            ->header('Cache-Control', 'public, max-age=31536000');
    }
}
