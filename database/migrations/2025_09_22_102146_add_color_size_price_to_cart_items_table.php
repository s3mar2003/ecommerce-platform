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
        Schema::table('cart_items', function (Blueprint $table) {
             $table->string('color')->nullable()->after('quantity');
            $table->string('size')->nullable()->after('color');
            $table->decimal('price', 10, 2)->after('size');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
             $table->dropColumn(['color', 'size', 'price']);
        });
    }
};
