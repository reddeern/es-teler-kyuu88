<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\LaporanPenjualan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    public function kasir()
    {
        $produks = Produk::where('status', 'aktif')->get();
        return view('kasir.index', compact('produks'));
    }

    public function checkoutSession(Request $request) 
    {
        $cart = json_decode($request->cart_data, true);
        
        if (!$cart) {
            return redirect()->back()->with('error', 'Keranjang kosong!');
        }

        $total = collect($cart)->sum(fn($item) => $item['harga'] * $item['quantity']);
        return view('kasir.input', compact('cart', 'total'));
    }

    public function store(Request $request) {
        $cart = json_decode($request->cart_data, true);
        $subtotal = collect($cart)->sum(fn($i) => $i['harga'] * $i['quantity']);
        
        $pajak = 0; 
        $total_akhir = $subtotal; 
        
        $trx = Transaksi::create([
            'nama_pelanggan' => $request->nama_pelanggan,
            'detail_produk' => $cart,
            'subtotal' => $subtotal,
            'pajak' => $pajak,
            'total_akhir' => $total_akhir,
            'total_harga' => $total_akhir, 
            'metode_pembayaran' => $request->metode_pembayaran,
            'tanggal' => Carbon::now()->toDateString(), // Tambahkan kolom tanggal jika ada
        ]);

        $hariIni = Carbon::now()->toDateString();
        $laporan = LaporanPenjualan::firstOrCreate(
            ['tanggal' => $hariIni],
            ['total_omset' => 0]
        );
        $laporan->increment('total_omset', $total_akhir);
    
        return redirect()->route('kasir.struk', $trx->id);
    }

    public function struk($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        return view('kasir.struk', compact('transaksi'));
    }

    public function index(Request $request)
    {
        $query = Transaksi::query();
    
        if ($request->filled('search')) {
            $query->where('nama_pelanggan', 'like', '%' . $request->search . '%');
        }
    
        $transaksi = $query->latest()->get();
    
        if ($request->ajax()) {
            return view('transaksi._table_rows', compact('transaksi'))->render();
        }
    
        return view('transaksi.index', compact('transaksi'));
    }

    public function show($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        return view('transaksi.detail', compact('transaksi'));
    }

    public function laporan(Request $request)
    {
        $total_omset_keseluruhan = LaporanPenjualan::sum('total_omset');

        $query = LaporanPenjualan::query();

        $start_date = $request->get('start_date', Carbon::now()->toDateString());
        $end_date = $request->get('end_date', Carbon::now()->toDateString());

        $query->whereBetween('tanggal', [$start_date, $end_date]);

        $laporan_data = $query->orderBy('tanggal', 'desc')->get();
        
        $total_omset_periode = $laporan_data->sum('total_omset');
        $jumlah_hari = $laporan_data->count();

        return view('laporan.index', compact(
            'laporan_data', 
            'total_omset_periode', 
            'total_omset_keseluruhan', 
            'jumlah_hari'
        ));
    }
}