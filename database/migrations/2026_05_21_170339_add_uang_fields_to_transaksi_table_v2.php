<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            if (!Schema::hasColumn('transaksi', 'uang_bayar')) {
                $table->decimal('uang_bayar', 15, 2)->nullable()->after('metode_pembayaran');
            }
            if (!Schema::hasColumn('transaksi', 'uang_kembali')) {
                $table->decimal('uang_kembali', 15, 2)->nullable()->after('uang_bayar');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn(['uang_bayar', 'uang_kembali']);
        });
    }
};
