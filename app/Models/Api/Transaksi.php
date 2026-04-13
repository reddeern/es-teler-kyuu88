<?php

namespace App\Models\Api;

use App\Models\Transaksi as BaseTransaksi;

class Transaksi extends BaseTransaksi
{
    // API specific logic if any
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
