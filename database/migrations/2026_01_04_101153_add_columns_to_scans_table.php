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
        Schema::table('scans', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('image_path')->nullable();
            $table->json('ai_result')->nullable();
            $table->json('detected_items')->nullable();
            $table->string('status')->default('pending');
            $table->text('admin_notes')->nullable();
            $table->foreignId('confirmed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->decimal('total_weight', 8, 2)->nullable();
            $table->integer('poin_earned')->default(0);
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
                'user_id', 'image_path', 'ai_result', 'detected_items', 
                'status', 'admin_notes', 'confirmed_by', 'total_weight', 'poin_earned'
            ]);
        });
    }
};
