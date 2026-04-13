<?php

namespace App\Models\Api;

use App\Models\LaporanPenjualan as BaseLaporanPenjualan;

class LaporanPenjualan extends BaseLaporanPenjualan
{
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
