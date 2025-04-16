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
        Schema::table('societies', function (Blueprint $table) {
            $table->longText('paiement_terms')->nullable();
        });
        DB::table('societies')->update(['paiement_terms' => '']);
        Schema::table('societies', function (Blueprint $table) {
            $table->longText('paiement_terms')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('societies', function (Blueprint $table) {
            $table->dropColumn('paiement_terms');
        });
    }
};
