<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected $fillable = [
        'nama_pelanggan',
        'detail_produk',
        'subtotal',
        'pajak',
        'total_akhir',
        'total_harga',
        'metode_pembayaran',
        'uang_bayar',
        'uang_kembali'
    ];

    protected $casts = [
        'detail_produk' => 'array',
    ];
}
