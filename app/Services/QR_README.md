# QR Code Module Documentation

## Struktur Folder

```
app/
├── Http/
│   └── Controllers/
│       └── QrController.php          # Controller untuk handling QR requests
└── Services/
    └── QrCodeService.php              # Service class untuk logic QR

resources/
└── views/
    └── qr/
        ├── index.blade.php            # View untuk pindai QR
        └── labels.blade.php           # View untuk cetak label QR

routes/
└── web.php                            # Route definitions
```

## Routes

### Public Routes
```
GET /qr/generate/{code}               # Generate QR code image
```

### Protected Routes (Require Auth)
```
GET /qr/pindai                        # Pindai dan lacak QR
GET /qr/labels                        # Cetak label QR
```

## Usage

### Generate QR Code
```
GET /qr/generate/BRG-TABL-482
```

Response: PNG image

### Scan QR with Camera
```
GET /qr/pindai
```

### Print QR Labels
```
GET /qr/labels
```

## Service Layer

### QrCodeService
Located at: `app/Services/QrCodeService.php`

**Methods:**
- `generateQrImage(string $code): string` - Generate QR code image dari kode/text

**Usage:**
```php
$qrService = new QrCodeService();
$qrImage = $qrService->generateQrImage('BRG-TABL-482');
```

## Features

- ✅ Generate QR code dari text/kode
- ✅ Pindai QR dengan kamera
- ✅ Upload foto untuk scan QR
- ✅ Cetak label QR
- ✅ Cache support untuk performa
- ✅ API gratis (qrserver.com)
