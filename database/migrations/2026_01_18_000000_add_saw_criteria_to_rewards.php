<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('rewards', function (Blueprint $table) {
            // C1: Berat (Benefit)
            $table->float('berat')->default(0)->after('description');
            // C2: Nilai Poin (Benefit) - Distinct from Harga Poin (Cost)
            $table->integer('nilai_poin')->default(0)->after('berat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rewards', function (Blueprint $table) {
            $table->dropColumn(['berat', 'nilai_poin']);
        });
    }
};
