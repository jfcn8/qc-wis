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
        Schema::create('temp_ris', function (Blueprint $table) {
            $table->id('temp_ris_id');
            $table->date('date_request');
            $table->string('purpose');
            $table->integer('item_id')->unsigned();
            $table->integer('action')->comment('1 RIS, 2 Delivery, 3 Initial');
            $table->integer('quantity');
            $table->integer('office_id')->unsigned();
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
        //
    }
};
