@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <h2 class="text-3xl font-black text-gray-800 mb-8 uppercase text-center tracking-widest">Detail Pembayaran</h2>

    <div class="bg-[#F2E3B6] p-10 rounded-[40px] shadow-2xl border-4 border-white/50">
        <form action="{{ route('kasir.store') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="cart_data" value="{{ json_encode($cart) }}">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <div>
                        <label class="block font-black text-gray-700 mb-2">NAMA PELANGGAN</label>
                        <input type="text" name="nama_pelanggan" autofocus required class="w-full p-4 rounded-2xl border-none shadow-inner outline-none focus:ring-4 focus:ring-pink-300 font-bold">
                    </div>
                    <div>
                        <label class="block font-black text-gray-700 mb-2">METODE PEMBAYARAN</label>
                        <div class="flex gap-4">
                            <label class="flex-1">
                                <input type="radio" name="metode_pembayaran" value="QRIS" class="hidden peer" required onchange="hitungKembali()">
                                <div class="p-4 text-center bg-white rounded-2xl font-black peer-checked:bg-pink-500 peer-checked:text-white cursor-pointer shadow-md">QRIS</div>
                            </label>
                            <label class="flex-1">
                                <input type="radio" name="metode_pembayaran" value="CASH" class="hidden peer" checked onchange="hitungKembali()">
                                <div class="p-4 text-center bg-white rounded-2xl font-black peer-checked:bg-pink-500 peer-checked:text-white cursor-pointer shadow-md">CASH</div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="bg-white/40 p-6 rounded-[32px] border border-white/50 space-y-4">
                    <div class="flex justify-between font-black text-gray-800 text-xl pt-2 pb-4 border-b border-dashed border-gray-400">
                        <span>TOTAL:</span>
                        <span id="total_akhir_view" data-val="{{ $total }}">Rp {{ number_format($total) }}</span>
                    </div>
                    
                    <div>
                        <label class="block font-black text-gray-700 mb-2 uppercase">Uang Terima</label>
                        <div class="relative">
                            <span class="absolute left-4 top-4 font-black text-green-700 text-2xl">Rp</span>
                            <input type="number" name="uang_terima" id="uang_terima" oninput="hitungKembali()" required class="w-full p-4 pl-14 rounded-2xl border-none shadow-inner text-3xl font-black text-green-600 outline-none">
                        </div>
                    </div>

                    <div class="pt-2">
                        <label class="block font-black text-gray-700 mb-1">KEMBALI</label>
                        <div id="uang_kembali" class="text-4xl font-black text-pink-500">Rp 0</div>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full py-6 bg-pink-500 text-white rounded-3xl text-3xl font-black mt-6 shadow-xl uppercase hover:brightness-110 transition-all">PROSES & CETAK <i class="fas fa-print ml-2"></i></button>
        </form>
    </div>
</div>

<script>
    function hitungKembali() {
        // Ambil total wajib bayar
        const totalWajib = parseInt(document.getElementById('total_akhir_view').getAttribute('data-val'));
        
        const metodeTerpilih = document.querySelector('input[name="metode_pembayaran"]:checked');
        const inputTerima = document.getElementById('uang_terima');
        const displayKembali = document.getElementById('uang_kembali');

        if (!metodeTerpilih) return;

        if (metodeTerpilih.value === 'QRIS') {
            // Pas pilih QRIS: Isi otomatis & kunci
            inputTerima.value = totalWajib; 
            inputTerima.readOnly = true;    
        } else {
            // Pas balik ke CASH: Buka kunci & kosongkan input
            inputTerima.readOnly = false;
            // Cek kalau sebelumnya bekas auto-fill QRIS, kita kosongin biar bisa ngetik manual
            if (inputTerima.value == totalWajib) {
                inputTerima.value = '';
            }
        }

        // Hitung ulang kembalian
        const terima = parseInt(inputTerima.value) || 0;
        const kembali = terima - totalWajib;
        
        displayKembali.innerText = 'Rp ' + (kembali >= 0 ? kembali.toLocaleString('id-ID') : 0);
        displayKembali.style.color = kembali >= 0 ? '#16a34a' : '#ec4899';
    }

    // Event listener biar pas ganti radio button langsung gerak
    document.querySelectorAll('input[name="metode_pembayaran"]').forEach(radio => {
        radio.addEventListener('change', hitungKembali);
    });

    window.onload = hitungKembali;
</script>
@endsection