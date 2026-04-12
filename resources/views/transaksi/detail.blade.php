@extends('layouts.app')

@section('content')

<style>
    @keyframes slideFromBottom {
        from { opacity: 0; transform: translateY(30px); filter: blur(5px); }
        to { opacity: 1; transform: translateY(0); filter: blur(0); }
    }

    @keyframes itemSlideUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .detail-card {
        background: #F2E3B6; /* Warna krem khas Kyuu 88 */
        padding: 40px;
        border-radius: 30px;
        max-width: 600px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        animation: slideFromBottom 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        border: 2px solid white;
    }

    .product-item {
        opacity: 0;
        animation: itemSlideUp 0.5s ease-out forwards;
    }

    .btn-struk {
        background: #ec4899;
        color: white;
        padding: 12px 25px;
        border-radius: 15px;
        font-weight: 900;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 0 #be185d;
    }

    .btn-struk:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 0 #be185d;
        background: #f472b6;
    }

    .btn-struk:active {
        transform: translateY(2px);
        box-shadow: none;
    }
</style>

<div class="max-w-4xl mx-auto">
    <h2 class="text-3xl font-black text-gray-800 italic uppercase tracking-tighter mb-8" style="animation: slideFromBottom 0.5s ease-out;">
        Detail Transaksi
    </h2>

    <div class="detail-card">
        <div style="animation: itemSlideUp 0.5s ease-out 0.2s forwards; opacity: 0;">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Pelanggan</p>
                    <p class="text-xl font-bold text-gray-800">{{ $transaksi->nama_pelanggan ?? 'Umum' }}</p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Metode</p>
                    <span class="bg-white px-3 py-1 rounded-full text-xs font-black border border-black/5 uppercase">
                        {{ $transaksi->metode_pembayaran }}
                    </span>
                </div>
            </div>
            <p class="mt-4 text-xs font-bold text-gray-500 uppercase italic">
                <i class="far fa-calendar-alt mr-1"></i> {{ $transaksi->created_at->translatedFormat('d F Y • H:i') }}
            </p>
        </div>

        <hr style="margin:25px 0; border: 0; border-top: 2px dashed rgba(0,0,0,0.05);">

        <div class="space-y-4">
            @foreach($transaksi->detail_produk as $index => $item)
            <div class="product-item flex justify-between items-center" style="animation-delay: {{ 0.4 + ($index * 0.1) }}s">
                <div>
                    <span class="block font-black text-gray-800 uppercase text-sm">{{ $item['nama_produk'] ?? $item['nama'] }}</span>
                    <span class="text-xs font-bold text-pink-400">x{{ $item['quantity'] ?? $item['qty'] }}</span>
                </div>
                <span class="font-black text-gray-700">Rp {{ number_format(($item['harga'] ?? 0) * ($item['quantity'] ?? $item['qty'] ?? 1), 0, ',', '.') }}</span>
            </div>
            @endforeach
        </div>

        <hr style="margin:25px 0; border: 0; border-top: 2px dashed rgba(0,0,0,0.05);">

        <div style="animation: itemSlideUp 0.5s ease-out 0.8s forwards; opacity: 0;" class="space-y-1">
            <div class="flex justify-between text-sm font-bold text-gray-500">
                <span>Subtotal</span>
                <span>Rp {{ number_format($transaksi->subtotal) }}</span>
            </div>
            <div class="flex justify-between text-sm font-bold text-gray-500">
                <span>Pajak (0%)</span>
                <span>Rp 0</span>
            </div>
            <div class="flex justify-between items-center pt-4">
                <span class="font-black text-gray-800 uppercase tracking-tighter">Total Akhir</span>
                <span class="text-3xl font-black text-pink-500 italic">
                    Rp {{ number_format($transaksi->total_akhir) }}
                </span>
            </div>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="mt-10 flex items-center gap-6" style="animation: itemSlideUp 0.5s ease-out 1s forwards; opacity: 0;">
        <a href="{{ route('transaksi.index') }}" class="text-gray-400 hover:text-gray-800 font-black text-xs uppercase tracking-widest no-underline transition-all">
            ← Kembali ke Riwayat
        </a>
        
        <a href="{{ route('kasir.struk', $transaksi->id) }}" class="btn-struk">
            <i class="fas fa-print"></i> LIHAT STRUK / CETAK
        </a>
    </div>
</div>

@endsection