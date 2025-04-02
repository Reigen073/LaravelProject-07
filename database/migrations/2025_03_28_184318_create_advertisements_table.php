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
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->integer('price');
            $table->string('category');
            $table->enum('type', ['buy', 'rent', 'bidding'])->default('buy');
            $table->enum('status', ['available', 'rented', 'sold'])->default('available');
            $table->text('qr_code')->nullable();
            $table->string('image')->nullable(); 
            $table->enum('condition', ['new', 'used', 'refurbished'])->default('used');
            $table->decimal('wear_rate', 5, 2)->nullable()->check('wear_rate >= 0 AND wear_rate <= 1');
            $table->date('expires_at')->nullable();
            $table->date('rental_start_date')->nullable();
            $table->integer('acquirer_user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advertisements');
    }
};
