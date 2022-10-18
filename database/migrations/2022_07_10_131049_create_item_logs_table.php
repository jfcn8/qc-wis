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
        Schema::create('item_logs', function (Blueprint $table) {
            $table->id('item_log_id');
            $table->date('date_request');
            $table->bigInteger('reference_id')->unsigned();
            $table->foreign('reference_id')
                  ->references('reference_id')
                  ->on('references')
                  ->onDelete('cascade');
            $table->integer('action')->comment('1 RIS, 2 Delivery, 3 Initial');
            $table->string('ris_no')->nullable();
            $table->integer('quantity');
            $table->integer('user_id')->unsigned();
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
        Schema::dropIfExists('item_logs');
    }
};
