<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained('reservations')->onDelete('cascade'); // 予約との関連付け
            $table->decimal('amount',8,2); // 支払金額
            $table->string('status')->default('pending'); // 支払いステータス(成功、失敗。未処理)
            $table->string('payment_method')->nullable(); // 支払い方法(stripe,paypalなど)
            $table->timestamps(); // 作成日時、更新日時
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
