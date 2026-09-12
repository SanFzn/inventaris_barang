<?php

namespace App\Http\Requests;

use App\Models\Barang;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBarangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        /** @var Barang $barang */
        $barang = $this->route('barang');

        return [
            'kode_barang' => 'required|string|max:50|unique:barang,kode_barang,' . $barang->id_barang . ',id_barang',
            'nama_barang' => 'required|string|max:100',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'id_lokasi' => 'required|exists:lokasi,id_lokasi',
            'spesifikasi' => 'nullable|string',
            'tgl_pembelian' => 'nullable|date',
            'file_qr' => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:2048',
        ];
    }
}
