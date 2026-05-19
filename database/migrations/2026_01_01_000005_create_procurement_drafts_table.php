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
        Schema::create('procurement_drafts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users'); // Kepala Lab
            $table->string('title');
            $table->year('year');
            $table->enum('status', ['draft', 'submitted', 'reviewed', 'finalized'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procurement_drafts');
    }
};