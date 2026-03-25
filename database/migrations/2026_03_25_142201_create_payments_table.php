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
        Schema::create('payments', function (Blueprint $table) {
            $table->id('payment_id');
            $table->foreignId('order_id')->constrained('orders', 'order_id')->cascadeOnDelete();

            $table->decimal('amount', 10, 2);
            $table->string('payment_type');
            $table->date('payment_date');
            $table->string('status')->default('pending');

            $table->timestamps();
        });
    }
};
