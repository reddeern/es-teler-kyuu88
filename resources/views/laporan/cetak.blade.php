<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan - Kyuu 88</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            .paper { box-shadow: none !important; margin: 0 !important; width: 100% !important; }
        }
        body { background: #525659; font-family: 'Inter', sans-serif; }
        .paper {
            background: white;
            width: 210mm;
            min-height: 297mm;
            margin: 30px auto;
            padding: 20mm;
            box-shadow: 0 0 20px rgba(0,0,0,0.3);
        }
    </style>
</head>
<body>

    <div class="no-print sticky top-0 bg-gray-900/80 backdrop-blur-md p-4 flex justify-center gap-4 z-50">
        <button onclick="window.close()" class="bg-red-500 text-white px-6 py-2 rounded-xl font-bold uppercase text-xs">Tutup Preview</button>
        <button onclick="window.print()" class="bg-[#b9db5a] text-gray-900 px-10 py-2 rounded-xl font-black uppercase text-xs shadow-[0_4px_0_#9dbb4d]">Cetak Sekarang</button>
    </div>

    <div class="paper">
        <div class="text-center mb-10 border-b-4 border-double border-gray-800 pb-6">
            <h1 class="text-5xl font-black italic tracking-tighter">KYUU 88</h1>
            <p class="text-sm font-bold text-gray-500 uppercase tracking-[0.3em]">Laporan Pendapatan Es Teler</p>
        </div>

        <div class="mb-8 flex justify-between items-end">
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase">Periode Laporan:</p>
                <p class="text-xl font-black text-gray-800">
                    {{ \Carbon\Carbon::parse(request('start_date'))->translatedFormat('d/m/Y') }} 
                    - 
                    {{ \Carbon\Carbon::parse(request('end_date'))->translatedFormat('d/m/Y') }}
                </p>
            </div>
            <div class="text-right">
                <p class="text-[10px] font-black text-gray-400 uppercase">Total Akumulasi:</p>
                <p class="text-3xl font-black text-pink-500">Rp {{ number_format($total_omset_periode) }}</p>
            </div>
        </div>

        <table class="w-full border-collapse">
            <thead>
                <tr class="border-b-2 border-gray-800">
                    <th class="py-4 text-left text-xs font-black uppercase">Tanggal Transaksi</th>
                    <th class="py-4 text-right text-xs font-black uppercase">Total Omset Harian</th>
                </tr>
            </thead>
            <tbody>
                @foreach($laporan_data as $data)
                <tr class="border-b border-gray-100">
                    <td class="py-4 font-bold text-gray-700">{{ \Carbon\Carbon::parse($data->tanggal)->translatedFormat('d F Y') }}</td>
                    <td class="py-4 text-right font-black text-gray-900">Rp {{ number_format($data->total_omset) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-20 flex justify-between items-center opacity-50">
            <div class="text-[9px] font-bold italic">
                Sistem Kasir Kyuu 88 | Dicetak pada {{ now()->format('d/m/Y H:i') }}
            </div>
            <div class="w-32 h-[1px] bg-gray-400"></div>
        </div>
    </div>

</body>
</html>