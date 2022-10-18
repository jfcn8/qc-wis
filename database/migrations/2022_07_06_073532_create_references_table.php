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
        Schema::create('references', function (Blueprint $table) {
            $table->id('reference_id');
            $table->string('reference');
            $table->integer('stock');
            $table->double('price', 8, 2);
            $table->bigInteger('item_id')->unsigned();
            $table->foreign('item_id')
                  ->references('item_id')
                  ->on('items')
                  ->onDelete('cascade');
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
        Schema::dropIfExists('references');
    }
};
