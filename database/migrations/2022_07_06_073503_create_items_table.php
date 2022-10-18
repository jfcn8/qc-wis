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
        Schema::create('items', function (Blueprint $table) {
            $table->id('item_id');
            $table->string('description');
            $table->string('stock_number');
            $table->bigInteger('article_id')->unsigned();
            $table->foreign('article_id')
                  ->references('article_id')
                  ->on('articles');
            $table->bigInteger('unit_id')->unsigned();
            $table->foreign('unit_id')
                  ->references('unit_id')
                  ->on('units');
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
        Schema::dropIfExists('items');
    }
};
