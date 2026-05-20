<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KasirController extends Controller
{
    public function getReceiptData($order_id)
    {
        // Cukup panggil find(), tidak perlu with('details') karena datanya ada di kolom 'detail_produk'
        $order = \App\Models\Transaksi::find($order_id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan!'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'server_time' => \Carbon\Carbon::now()->toDateTimeString(),
            'data_struk' => [
                'nomor_nota'        => 'INV-' . $order->id,
                'nama_pelanggan'    => $order->nama_pelanggan,
                'tgl_transaksi'     => $order->created_at->format('d-m-Y H:i:s'), // Format tanggal asli transaksi
                'items'             => $order->detail_produk, // Otomatis jadi Array karena cast di Model
                'subtotal'          => $order->subtotal,
                'total_akhir'       => $order->total_akhir,
                'total_harga'       => $order->total_akhir, // Ditambahkan agar dibaca sukses oleh C# d.total_harga

                // DATA PEMBAYARAN SEKARANG SUDAH SEJAJAR (TIDAK DIBUNGKUS LAGI)
                'metode_pembayaran' => $order->metode_pembayaran,
                'bayar'             => $order->uang_bayar,
                'kembali'           => $order->uang_kembali,
            ]
        ]);
    }
}