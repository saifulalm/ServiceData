<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MessageTransactionServiceData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_transaction_servicedata', function (Blueprint $table) {
            $table->bigIncrements('no')->autoIncrement();
            $table->string('idtrx')->primarykey()->index();
            $table->string('kode', '200');
            $table->string('tujuan', '20');
            $table->string('requestid')->nullable();
            $table->json('request');
            $table->json('response')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('message_transaction_servicedata');
    }
}
