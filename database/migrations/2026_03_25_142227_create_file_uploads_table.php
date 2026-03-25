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
        Schema::create('file_uploads', function (Blueprint $table) {
            $table->id('file_id');

            $table->foreignId('order_id')->constrained('orders', 'order_id')->cascadeOnDelete();

            $table->string('file_name');
            $table->string('file_path');
            $table->date('upload_date');

            $table->timestamps();
        });
    }
};
