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
       Schema::create('order_details', function (Blueprint $table) {
            $table->id('order_details_id');

            $table->foreignId('order_id')->constrained('orders', 'order_id')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products', 'product_id')->cascadeOnDelete();

            $table->integer('quantity');
            $table->string('size')->nullable();
            $table->string('color')->nullable();
            $table->string('paper_quality')->nullable();
            $table->text('special_instruction')->nullable();

            $table->timestamps();
        });
    }
};
