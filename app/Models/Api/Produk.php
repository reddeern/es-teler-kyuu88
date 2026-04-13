<?php

namespace App\Models\Api;

use App\Models\Produk as BaseProduk;

class Produk extends BaseProduk
{
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
