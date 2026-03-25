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
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->foreignId('user_id')->nullable()->constrained('users', 'user_id')->nullOnDelete();

            $table->string('customer_name');
            $table->string('email');
            $table->string('phone_number');

            $table->string('status')->default('pending');
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->date('order_date');

            $table->timestamps();
        });
    }
};
