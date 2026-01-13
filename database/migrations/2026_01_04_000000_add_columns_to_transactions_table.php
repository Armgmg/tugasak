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
        Schema::table('transactions', function (Blueprint $table) {
            // Add tipe_transaksi column if it doesn't exist
            if (!Schema::hasColumn('transactions', 'tipe_transaksi')) {
                $table->enum('tipe_transaksi', ['setor', 'tukar'])
                    ->default('setor')
                    ->after('user_id');
            }
            
            // Add reward column if it doesn't exist
            if (!Schema::hasColumn('transactions', 'reward')) {
                $table->string('reward')->nullable()->after('poin');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'tipe_transaksi')) {
                $table->dropColumn('tipe_transaksi');
            }
            if (Schema::hasColumn('transactions', 'reward')) {
                $table->dropColumn('reward');
            }
        });
    }
};
