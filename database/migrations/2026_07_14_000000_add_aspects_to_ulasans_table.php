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
        Schema::table('ulasans', function (Blueprint $table) {
            $table->string('sentimen_barang')->nullable()->after('label_sentimen');
            $table->string('sentimen_pengiriman')->nullable()->after('sentimen_barang');
            $table->string('sentimen_packaging')->nullable()->after('sentimen_pengiriman');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ulasans', function (Blueprint $table) {
            $table->dropColumn(['sentimen_barang', 'sentimen_pengiriman', 'sentimen_packaging']);
        });
    }
};
