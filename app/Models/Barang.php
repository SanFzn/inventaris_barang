<?php

namespace App\Models;

use App\Models\Kategori;
use App\Models\Lokasi;
use App\Models\Peminjaman;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';

    protected $primaryKey = 'id_barang';

    public $timestamps = false;

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'id_kategori',
        'id_lokasi',
        'spesifikasi',
        'tgl_pembelian',
        'status',
        'file_qr',
    ];

    protected $casts = [
        'tgl_pembelian' => 'date',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(Lokasi::class, 'id_lokasi', 'id_lokasi');
    }

    public function peminjaman(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'id_barang', 'id_barang');
    }

    /**
     * Get relevant Unsplash stock image URL for this asset
     */
    public function getImageUrlAttribute(): string
    {
        $name = strtolower($this->nama_barang ?? '');
        $category = strtolower($this->kategori->nama_kategori ?? '');

        if (str_contains($name, 'laptop') || str_contains($name, 'notebook') || str_contains($name, 'macbook')) {
            return 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=600&q=80';
        }
        if (str_contains($name, 'monitor') || str_contains($name, 'layar') || str_contains($name, 'display')) {
            return 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?auto=format&fit=crop&w=600&q=80';
        }
        if (str_contains($name, 'komputer') || str_contains($name, 'pc') || str_contains($name, 'cpu')) {
            return 'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?auto=format&fit=crop&w=600&q=80';
        }
        if (str_contains($name, 'printer') || str_contains($name, 'scanner') || str_contains($name, 'cetak')) {
            return 'https://images.unsplash.com/photo-1612815154858-60aa4c59eaa6?auto=format&fit=crop&w=600&q=80';
        }
        if (str_contains($name, 'tablet') || str_contains($name, 'ipad')) {
            return 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?auto=format&fit=crop&w=600&q=80';
        }
        if (str_contains($name, 'kursi') || str_contains($name, 'chair')) {
            return 'https://images.unsplash.com/photo-1580481077195-731b59f323a6?auto=format&fit=crop&w=600&q=80';
        }
        if (str_contains($name, 'meja') || str_contains($name, 'desk') || str_contains($name, 'table')) {
            return 'https://images.unsplash.com/photo-1518455027359-f3f8164ba6bd?auto=format&fit=crop&w=600&q=80';
        }
        if (str_contains($name, 'proyektor') || str_contains($name, 'projector')) {
            return 'https://images.unsplash.com/photo-1535016120720-40c646be5580?auto=format&fit=crop&w=600&q=80';
        }
        if (str_contains($name, 'mouse') || str_contains($name, 'keyboard')) {
            return 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?auto=format&fit=crop&w=600&q=80';
        }
        if (str_contains($name, 'kamera') || str_contains($name, 'camera')) {
            return 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?auto=format&fit=crop&w=600&q=80';
        }

        // Category fallbacks
        if (str_contains($category, 'elektronik')) {
            return 'https://images.unsplash.com/photo-1550009158-9ebf69173e03?auto=format&fit=crop&w=600&q=80';
        }
        if (str_contains($category, 'perabot') || str_contains($category, 'furniture')) {
            return 'https://images.unsplash.com/photo-1524758631624-e2822e304c36?auto=format&fit=crop&w=600&q=80';
        }
        if (str_contains($category, 'tulis') || str_contains($category, 'atk') || str_contains($category, 'office')) {
            return 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&w=600&q=80';
        }

        $stockPhotos = [
            'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1497215728101-856f4ea42174?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1499750310107-5fef28a66643?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=600&q=80',
        ];

        $index = abs((int) ($this->id_barang ?? 0)) % count($stockPhotos);
        return $stockPhotos[$index];
    }
}
