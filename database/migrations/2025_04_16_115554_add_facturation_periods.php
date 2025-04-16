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
        Schema::table('invoices', function (Blueprint $table) {
            $table->dateTime('period_start')->nullable();
            $table->dateTime('period_end')->nullable();
        });
        DB::table('invoices')->update(['period_start' => now(), 'period_end' => now()]);
        Schema::table('invoices', function (Blueprint $table) {
            $table->dateTime('period_start')->nullable(false)->change();
            $table->dateTime('period_end')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('period_start');
            $table->dropColumn('period_end');
        });
    }
};
