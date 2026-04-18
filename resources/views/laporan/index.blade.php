@extends('layouts.app')

@section('content')
<style>
    /* Styling Dasar agar mirip dengan referensi gambar */
    .bg-krem-custom { background-color: #F2E3B6; }
    .table-container { background-color: #E9D9AA; border-radius: 24px; padding: 24px; }
    .table-row-light { background-color: #F8F0D8; border-radius: 12px; margin-bottom: 8px; }
    .table-row-dark { background-color: #F2E3B6; border-radius: 12px; margin-bottom: 8px; }
    
    /* Overlay untuk Visual Preview */
    #print-preview-modal {
        display: none; 
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.85);
        z-index: 9999;
        overflow-y: auto;
        padding: 40px 0;
    }

    .paper-visual {
        background: white;
        width: 210mm;
        margin: 0 auto;
        padding: 20mm;
        min-height: 297mm;
        color: black;
    }

    @media print {
        body * { visibility: hidden; }
        #print-preview-modal, #print-preview-modal * { visibility: visible; }
        #print-preview-modal { position: absolute; top: 0; left: 0; width: 100%; background: white; padding: 0; }
        .no-print { display: none !important; }
        .paper-visual { box-shadow: none; margin: 0; width: 100%; padding: 10mm; border: none; }
    }
</style>

<div class="max-w-6xl mx-auto px-4 no-print">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-black text-gray-800 uppercase tracking-tighter italic text-pink-500">Omset Kyuu 88</h2>
            <p class="text-xs font-bold text-gray-400 tracking-widest uppercase">Laporan Akumulasi & Harian</p>
        </div>
        
        <div class="flex flex-wrap gap-2">
            <form action="{{ route('laporan.index') }}" method="GET" class="flex gap-2 bg-white p-2 rounded-2xl shadow-sm border-2 border-[#F2E3B6]">
                <input type="date" name="start_date" value="{{ $start_date }}" class="p-2 text-sm rounded-xl outline-none font-bold">
                <span class="self-center font-black text-pink-300">/</span>
                <input type="date" name="end_date" value="{{ $end_date }}" class="p-2 text-sm rounded-xl outline-none font-bold">
                <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded-xl font-black text-xs hover:bg-black transition-all">FILTER</button>
            </form>

            <button onclick="bukaPreview()" class="bg-pink-500 text-white px-6 py-2 rounded-2xl font-black text-xs shadow-[0_4px_0_rgb(190,40,100)] active:translate-y-1 active:shadow-none transition-all">
                <i class="fas fa-print mr-2"></i> PREVIEW
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
        <div class="bg-[#F2E3B6] p-8 rounded-[30px] shadow-sm border-b-4 border-black/10">
            <p class="text-gray-600 font-bold uppercase text-[10px] tracking-widest">Total Seluruh Pendapatan (All-Time)</p>
            <h3 class="text-4xl font-black text-gray-800 italic">Rp {{ number_format($total_omset_keseluruhan ?? $total_omset_periode) }}</h3>
            <p class="text-[10px] text-gray-400 font-bold mt-1 uppercase italic">*Akumulasi transaksi awal sampai sekarang</p>
        </div>

        <div class="bg-white p-8 rounded-[30px] shadow-sm border-2 border-[#F2E3B6] border-b-4 border-pink-400">
            <p class="text-pink-400 font-bold uppercase text-[10px] tracking-widest italic">Omset Pada Filter Terpilih</p>
            <h3 class="text-4xl font-black text-pink-500 italic">
                Rp {{ number_format($total_omset_periode) }}
            </h3>
            <p class="text-[10px] text-pink-300 font-bold mt-1 uppercase italic italic">
                {{ \Carbon\Carbon::parse($start_date)->format('d/m/y') }} - {{ \Carbon\Carbon::parse($end_date)->format('d/m/y') }}
            </p>
        </div>
    </div>

    <div class="bg-white rounded-[30px] shadow-sm border-2 border-[#F2E3B6] border-b-4 border-pink-400 overflow-hidden mb-8">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F2E3B6] text-gray-700 uppercase text-[10px] font-black tracking-widest border-b-4 border-black/10">
                        <th class="px-4 md:px-8 py-5">Tanggal Pemasukan</th>
                        <th class="px-4 md:px-8 py-5 text-right">Total Harian</th>
                    </tr>
                </thead>
                <tbody class="text-gray-800 font-bold text-sm">
                    @forelse($laporan_data as $index => $data)
                    <tr class="border-b border-[#F2E3B6] hover:bg-[#F8F0D8] transition-colors {{ ($index + 1) % 2 == 0 ? 'bg-white' : 'bg-gray-50/30' }}">
                        <td class="px-4 md:px-8 py-4 whitespace-nowrap capitalize">
                            {{ \Carbon\Carbon::parse($data->tanggal)->translatedFormat('l, d F Y') }}
                        </td>
                        <td class="px-4 md:px-8 py-4 text-right font-black text-pink-500 italic text-base md:text-lg">
                            Rp {{ number_format($data->total_omset) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="px-4 md:px-8 py-16 text-center text-gray-400 italic font-bold">
                            <div class="flex flex-col items-center justify-center gap-3">
                                <i class="fas fa-inbox text-4xl text-gray-200"></i>
                                <span>Tidak ada data pada rentang tanggal ini.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="print-preview-modal">
    <div class="max-w-[210mm] mx-auto mb-6 flex justify-between px-4 no-print">
        <button onclick="tutupPreview()" class="bg-white text-gray-800 px-8 py-3 rounded-2xl font-black border-2 border-gray-100 uppercase text-xs">KEMBALI</button>
        <button onclick="window.print()" class="bg-gray-900 text-white px-12 py-3 rounded-2xl font-black shadow-lg uppercase text-xs">CETAK SEKARANG</button>
    </div>

    <div class="paper-visual">
        <div class="text-center mb-16">
            <h1 class="text-5xl font-black italic tracking-tighter">KYUU 88</h1>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.4em] mt-2">Laporan Rekapitulasi Penjualan</p>
            <div class="w-16 h-1.5 bg-pink-500 mx-auto mt-4 rounded-full"></div>
        </div>

        <table class="w-full">
            <thead>
                <tr class="border-b-4 border-black text-[11px] uppercase font-black text-gray-400 italic">
                    <th class="py-5 text-left pb-4">Tanggal Rekap</th>
                    <th class="py-5 text-right pb-4 text-black">Total Pemasukan</th>
                </tr>
            </thead>
            <tbody class="text-sm font-bold">
                @foreach($laporan_data as $data)
                <tr class="border-b border-gray-50">
                    <td class="py-6 text-lg text-gray-800">
                        {{ \Carbon\Carbon::parse($data->tanggal)->translatedFormat('l, d F Y') }}
                    </td>
                    <td class="py-6 text-right font-black text-2xl italic">
                        Rp {{ number_format($data->total_omset) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td class="py-12 text-right font-black uppercase text-[10px] tracking-[0.2em] text-gray-400">Total Filtered Revenue</td>
                    <td class="py-12 text-right font-black text-4xl text-pink-500 italic">
                        Rp {{ number_format($total_omset_periode) }}
                    </td>
                </tr>
            </tfoot>
        </table>

        <div class="mt-32 text-center text-[10px] text-gray-300 font-bold uppercase tracking-widest border-t border-dashed pt-8">
            Laporan Resmi Kyuu 88 • {{ now()->translatedFormat('d F Y H:i') }}
        </div>
    </div>
</div>

<script>
    function bukaPreview() {
        document.getElementById('print-preview-modal').style.display = 'block';
        document.body.style.overflow = 'hidden'; 
    }
    function tutupPreview() {
        document.getElementById('print-preview-modal').style.display = 'none';
        document.body.style.overflow = 'auto'; 
    }
</script>
@endsection