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
            $table->dateTime('validated_at')->nullable();
        });
        DB::table('invoices')->where('validated', '=', 1)->update([
            'validated_at' => now()
        ]);
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('validated');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->boolean('validated')->default(false);
        });
        DB::table('invoices')->whereNot('validated_at', '=', null)->update([
            'validated' => true
        ]);
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('validated_at');
        });
    }
};
