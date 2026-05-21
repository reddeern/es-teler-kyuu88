<?php
namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class KasirController extends Controller
{
    public function index()
    {
        // Hanya ambil produk aktif
        $produks = Produk::where('status', 'aktif')->get();
        return view('kasir.index', compact('produks'));
    }

    public function store(Request $request)
    {
        // Validasi
        $request->validate([
            'nama_pelanggan' => 'required',
            'metode_pembayaran' => 'required',
            'cart_data' => 'required' // Kita kirim JSON dari JS
        ]);

        $cart = json_decode($request->cart_data, true);
        
        $normalized_cart = [];
        $subtotal = 0;

        foreach ($cart as $item) {
            $id_produk = $item['id'] ?? null;
            $quantity = $item['quantity'] ?? 1;

            $produk = Produk::find($id_produk);
            
            if ($produk) {
                $harga = $produk->harga_produk;
                $nama = $produk->nama_produk;

                $normalized_cart[] = [
                    'id' => $id_produk,
                    'nama' => $nama,
                    'harga' => (int)$harga,
                    'quantity' => (int)$quantity,
                ];
                $subtotal += $harga * $quantity;
            } else {
                // Fallback jika produk tidak ditemukan di DB
                $harga = $item['harga'] ?? 0;
                $nama = $item['nama'] ?? 'Produk Tidak Diketahui';
                
                $normalized_cart[] = [
                    'id' => $id_produk,
                    'nama' => $nama,
                    'harga' => (int)$harga,
                    'quantity' => (int)$quantity,
                ];
                $subtotal += $harga * $quantity;
            }
        }

        $total_akhir = $subtotal;

        $transaksi = Transaksi::create([
            'nama_pelanggan' => $request->nama_pelanggan,
            'detail_produk' => $normalized_cart,
            'subtotal' => $subtotal,
            'total_akhir' => $total_akhir,
            'total_harga' => $total_akhir,
            'metode_pembayaran' => $request->metode_pembayaran,
        ]);

        return redirect()->route('kasir.struk', $transaksi->id)->with('success', 'Transaksi Berhasil!');
    }

    public function struk($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        return view('kasir.struk', compact('transaksi'));
    }
}