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
        Schema::create('related_advertisement', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advertisement_id')->constrained('advertisements')->onDelete('cascade');
            $table->foreignId('related_advertisement_id')->constrained('advertisements')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('related_advertisement');
    }
};
