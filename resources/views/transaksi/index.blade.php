@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-black text-gray-800 italic uppercase tracking-tighter">Riwayat Transaksi</h2>
            <p class="text-xs font-bold text-gray-400 tracking-widest uppercase">Kyuu 88 Order History</p>
        </div>
        
        <form action="{{ route('transaksi.index') }}" method="GET" class="relative">
            <input type="text" name="search" placeholder="Cari nama pelanggan..." 
                   class="bg-white border-2 border-[#F2E3B6] rounded-2xl px-6 py-3 text-sm font-bold outline-none focus:border-pink-300 transition-all w-64 shadow-sm"
                   value="{{ request('search') }}">
            <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>

    <div class="bg-[#E9D9AA] p-4 rounded-[30px] shadow-xl overflow-hidden">
        <table class="w-full text-left border-separate border-spacing-y-2">
            <thead>
                <tr class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em]">
                    <th class="px-6 py-4">Waktu</th>
                    <th class="px-6 py-4">Pelanggan</th>
                    <th class="px-6 py-4 text-center">Metode</th>
                    <th class="px-6 py-4 text-right">Total Akhir</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaksi as $trx)
                <tr class="bg-[#F8F0D8] hover:bg-white transition-all group">
                    <td class="px-6 py-5 rounded-l-2xl font-bold text-gray-500 text-xs">
                        {{ $trx->created_at->format('H:i') }}
                        <span class="block text-[9px] font-normal">{{ $trx->created_at->format('d/m/Y') }}</span>
                    </td>
                    <td class="px-6 py-5 font-black text-gray-800 uppercase italic">{{ $trx->nama_pelanggan ?? 'Umum' }}</td>
                    <td class="px-6 py-5 text-center">
                        <span class="px-3 py-1 bg-white rounded-full text-[10px] font-black border border-black/5 uppercase">
                            {{ $trx->metode_pembayaran }}
                        </span>
                    </td>
                    <td class="px-6 py-5 text-right font-black text-pink-500 text-lg italic">
                        Rp {{ number_format($trx->total_akhir) }}
                    </td>
                    <td class="px-6 py-5 rounded-r-2xl text-center">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('transaksi.show', $trx->id) }}" 
                               class="bg-gray-800 text-white p-2 rounded-xl hover:bg-black transition-all shadow-md active:scale-90"
                               title="Detail">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                            <a href="{{ route('kasir.struk', $trx->id) }}" 
                               class="bg-pink-500 text-white p-2 rounded-xl hover:bg-pink-600 transition-all shadow-md active:scale-90"
                               title="Cetak Struk">
                                <i class="fas fa-print text-xs"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection