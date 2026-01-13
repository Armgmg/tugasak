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
        Schema::table('scans', function (Blueprint $table) {
            if (!Schema::hasColumn('scans', 'user_id')) {
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('scans', 'image_path')) {
                $table->string('image_path')->nullable();
            }
            if (!Schema::hasColumn('scans', 'ai_result')) {
                $table->json('ai_result')->nullable();
            }
            if (!Schema::hasColumn('scans', 'detected_items')) {
                $table->json('detected_items')->nullable();
            }
            if (!Schema::hasColumn('scans', 'status')) {
                $table->string('status')->default('pending');
            }
            if (!Schema::hasColumn('scans', 'admin_notes')) {
                $table->text('admin_notes')->nullable();
            }
            if (!Schema::hasColumn('scans', 'confirmed_by')) {
                $table->foreignId('confirmed_by')->nullable()->constrained('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('scans', 'total_weight')) {
                $table->decimal('total_weight', 8, 2)->nullable();
            }
            if (!Schema::hasColumn('scans', 'poin_earned')) {
                $table->integer('poin_earned')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scans', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['user_id']);
            $table->dropForeignKeyIfExists(['confirmed_by']);
            $table->dropColumn([
                'user_id',
                'image_path',
                'ai_result',
                'detected_items',
                'status',
                'admin_notes',
                'confirmed_by',
                'total_weight',
                'poin_earned'
            ]);
        });
    }
};
