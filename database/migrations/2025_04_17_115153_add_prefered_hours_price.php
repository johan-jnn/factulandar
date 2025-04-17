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
        Schema::table('clients', function (Blueprint $table) {
            $table->float('prefered_hours_price', 2)->nullable();
        });
        DB::table('clients')->update(['prefered_hours_price' => 0]);
        Schema::table('clients', function (Blueprint $table) {
            $table->float('prefered_hours_price', 2)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('prefered_hours_price');
        });
    }
};
