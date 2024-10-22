<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('your_table_name', function (Blueprint $table) {
            $table->boolean('wants_reminder')->default(0); // カラムの追加
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
public function down()
{
    Schema::table('your_table_name', function (Blueprint $table) {
        $table->dropColumn('wants_reminder'); // ロールバックのためのカラム削除
    });
}
};
