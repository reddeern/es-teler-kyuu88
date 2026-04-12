@extends('layouts.app')

@section('content')
<style>
    .printer-wrapper { position: relative; overflow: hidden; padding-top: 4px; }
    .printer-slot { height: 12px; background: #222; border-radius: 6px 6px 0 0; position: relative; z-index: 30; }
    @keyframes printerPrint { 0% { transform: translateY(-100%); } 100% { transform: translateY(0); } }
    .animate-receipt { animation: printerPrint 2s cubic-bezier(0.45, 0.05, 0.55, 0.95) forwards; transform-origin: top; }
    .receipt-body { background: white; padding: 30px; font-family: 'Courier New', Courier, monospace; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
    @media print { .no-print, .printer-slot { display: none !important; } .receipt-body { box-shadow: none !important; border: none !important; padding: 0 !important; } }
</style>

<div class="max-w-md mx-auto pb-20 px-4">
    <div class="printer-slot no-print"></div>
    <div class="printer-wrapper">
        <div class="animate-receipt">
            <div class="receipt-body border-t-[8px] border-pink-500">
                <div class="text-center mb-6">
                    <h1 class="text-3xl font-black italic tracking-tighter text-gray-800">KYUU 88</h1>
                    <p class="text-[9px] text-gray-400 uppercase tracking-widest mt-1">Es Teler & Minuman Segar</p>
                    <div class="border-b-2 border-dashed border-gray-100 my-4"></div>
                    <p class="text-[11px] font-bold text-gray-500">{{ $transaksi->created_at->format('d/m/Y H:i:s') }}</p>
                </div>

                <div class="space-y-4 mb-8">
                    @foreach($transaksi->detail_produk as $item)
                    <div class="flex justify-between items-end">
                        <div class="flex flex-col">
                            <span class="font-black text-gray-800 text-sm uppercase">{{ $item['nama'] }}</span>
                            <span class="text-[10px] text-gray-400">{{ $item['quantity'] }} x Rp {{ number_format($item['harga']) }}</span>
                        </div>
                        <span class="font-bold text-gray-700 text-sm">Rp {{ number_format($item['harga'] * $item['quantity']) }}</span>
                    </div>
                    @endforeach
                </div>

                <div class="border-t-2 border-dashed border-gray-100 pt-5 space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-base font-black text-gray-800 tracking-tighter uppercase">Metode</span>
                        <span class="text-sm font-bold text-gray-700">{{ $transaksi->metode_pembayaran }}</span>
                    </div>
                    <div class="flex justify-between items-center pt-4 border-t-2 border-double border-gray-100">
                        <span class="text-lg font-black text-gray-800 tracking-tighter">TOTAL</span>
                        <span class="text-3xl font-black text-pink-600">Rp {{ number_format($transaksi->total_akhir) }}</span>
                    </div>
                </div>
                <div class="mt-10 text-center italic text-[10px] font-bold text-gray-300">~ Thank You for Coming! ~</div>
            </div>
        </div>
    </div>

    <div class="mt-10 flex gap-3 no-print">
        <a href="{{ route('kasir.index') }}" class="flex-1 bg-white text-center py-4 rounded-2xl font-black text-gray-400 border-2 border-gray-100">KEMBALI</a>
        <button onclick="window.print()" class="flex-[1.5] bg-[#b9db5a] text-gray-800 py-4 rounded-2xl font-black shadow-[0_6px_0_#9dbb4d]">CETAK STRUK</button>
    </div>
</div>
@endsection