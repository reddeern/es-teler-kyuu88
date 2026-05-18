<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KasirController extends Controller
{
    public function getReceiptData($order_id)
    {
        // Mencari data transaksi berdasarkan namespace model yang benar (App\Models\Api\Transaksi)
        $order = \App\Models\Api\Transaksi::find($order_id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan!'
            ], 404);
        }

        // Ambil data array detail_produk dari kolom database
        $items = $order->detail_produk;
        $formatItems = [];

        // Lakukan perulangan untuk menyisipkan nama_produk berdasarkan id_produk
        if (is_array($items) || is_object($items)) {
            foreach ($items as $item) {
                $idProduk = $item['id_produk'] ?? null;
                
                // Cari nama produk dari tabel produk menggunakan model App\Models\Api\Produk
                $produk = \App\Models\Api\Produk::find($idProduk);
                
                $formatItems[] = [
                    'id_produk'   => $idProduk,
                    'quantity'    => $item['quantity'] ?? ($item['qty'] ?? 1),
                    'harga'       => $item['harga'] ?? 0,
                    // Jika produk ditemukan di database pakai nama asli, jika tidak pakai ID sebagai cadangan
                    'nama_produk' => $produk ? $produk->nama_produk : 'Produk ID: ' . $idProduk
                ];
            }
        }

        return response()->json([
            'success' => true,
            'server_time' => Carbon::now()->toDateTimeString(),
            'data_struk' => [
                'nomor_nota' => 'INV-' . $order->id,
                'nama_pelanggan' => $order->nama_pelanggan,
                'tgl_transaksi' => $order->created_at->format('d-m-Y H:i:s'), 
                'items' => $formatItems, // Sudah diganti menggunakan data yang ada nama produknya
                'subtotal' => $order->subtotal,
                'pajak' => $order->pajak,
                'total_akhir' => $order->total_akhir,
                'pembayaran' => [
                    'metode' => $order->metode_pembayaran,
                    'bayar' => $order->uang_bayar,
                    'kembali' => $order->uang_kembali,
                ]
            ]
        ]);
    }
}
