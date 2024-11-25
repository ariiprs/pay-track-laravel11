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
        Schema::create('debts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('debitur_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();

            $table->string('slug');

            $table->unsignedBigInteger('debt_amount');
            //ini merupakan jumlah hutang yang dipinjam
            $table->unsignedBigInteger('monthly_payment');
            //ini merupakan jumlah cicilan perbulan yang harus dibayar

            $table->string('borrow_date'); //tanggal pertama kali meminjam
            $table->string('deadline_payment_date'); //tanggal terakhir pembayaran
            $table->boolean('debt_status');
            //ini merupakan status dari hutang yang dipinjam (lunas/belum lunas)

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('debts');
    }
};
