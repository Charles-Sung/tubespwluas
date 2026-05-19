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
        Schema::create('item_receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('procurement_detail_id')->constrained('procurement_details');
            $table->integer('quantity_received');
            $table->date('receipt_date');
            $table->foreignId('user_id')->constrained('users'); // Staf Admin
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_receipts');
    }
};