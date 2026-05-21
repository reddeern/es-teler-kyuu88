<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api\Produk;
use App\Models\Api\Transaksi;
use App\Models\Api\LaporanPenjualan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TransaksiApiController extends Controller
{
    // =====================================
    // GET PRODUK AKTIF (UNTUK KASIR DEVICE)
    // =====================================
    public function produkAktif()
    {
        $produks = Produk::where('status', 'aktif')->get();

        return response()->json([
            'success' => true,
            'data' => $produks
        ]);
    }

    // =====================================
    // STORE TRANSAKSI
    // =====================================
    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required',
            'metode_pembayaran' => 'required',
            'cart_data' => 'required|array',
            'uang_bayar' => 'nullable|numeric',
            'uang_kembali' => 'nullable|numeric'
        ]);

        DB::beginTransaction();

        try {
            $normalized_cart = [];
            $subtotal = 0;

            foreach ($request->cart_data as $item) {
                $id_produk = $item['id'] ?? $item['id_produk'] ?? null;
                $quantity = $item['quantity'] ?? $item['qty'] ?? 1;

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
                    // Fallback jika produk tidak ditemukan di DB, tapi ada di request
                    $harga = $item['harga'] ?? $item['harga_produk'] ?? 0;
                    $nama = $item['nama'] ?? $item['nama_produk'] ?? 'Produk Tidak Diketahui';
                    
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

            // Jika uang_bayar tidak dikirim (misal QRIS), set otomatis sama dengan total_akhir
            $uang_bayar = $request->uang_bayar ?? $total_akhir;
            $uang_kembali = $request->uang_kembali ?? ($uang_bayar - $total_akhir);

            // simpan transaksi
            $trx = Transaksi::create([
                'nama_pelanggan' => $request->nama_pelanggan,
                'detail_produk' => $normalized_cart,
                'subtotal' => $subtotal,
                'total_akhir' => $total_akhir,
                'total_harga' => $total_akhir,
                'metode_pembayaran' => $request->metode_pembayaran,
                'uang_bayar' => $uang_bayar,
                'uang_kembali' => $uang_kembali,
            ]);

            // update laporan otomatis
            $hariIni = Carbon::now()->toDateString();

            $laporan = LaporanPenjualan::firstOrCreate(
                ['tanggal' => $hariIni],
                ['total_omset' => 0]
            );

            $laporan->increment('total_omset', $total_akhir);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil',
                'data' => $trx
            ], 201);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal transaksi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // =====================================
    // LIST TRANSAKSI + SEARCH
    // =====================================
    public function index(Request $request)
    {
        $query = Transaksi::query();

        if ($request->filled('search')) {
            $query->where('nama_pelanggan', 'like', '%' . $request->search . '%');
        }

        $transaksi = $query->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $transaksi
        ]);
    }

    // =====================================
    // DETAIL TRANSAKSI
    // =====================================
    public function show($id)
    {
        $transaksi = Transaksi::find($id);

        if (!$transaksi) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $transaksi
        ]);
    }

    // =====================================
    // LAPORAN PENJUALAN
    // =====================================
    public function laporan(Request $request)
    {
        $query = LaporanPenjualan::query();

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [
                $request->start_date,
                $request->end_date
            ]);
        }

        $laporan_data = $query->orderBy('tanggal', 'desc')->get();

        return response()->json([
            'success' => true,
            'total_omset_periode' => $laporan_data->sum('total_omset'),
            'jumlah_hari' => $laporan_data->count(),
            'data' => $laporan_data
        ]);
    }
}