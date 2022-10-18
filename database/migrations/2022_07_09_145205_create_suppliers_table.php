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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id('supplier_id');
            $table->string('supplier');
            $table->string('other_information')->nullable();
            $table->timestamps();
        });

        Schema::create('deliveries', function (Blueprint $table) {
            $table->id('delivery_id');
            $table->date('delivery_date');
            $table->integer('stock');
            $table->bigInteger('supplier_id')->unsigned();
            $table->foreign('supplier_id')
                  ->references('supplier_id')
                  ->on('suppliers')
                  ->onDelete('cascade');
            $table->bigInteger('item_id')->unsigned();
            $table->foreign('item_id')
                    ->references('item_id')
                    ->on('items')
                    ->onDelete('cascade');
            $table->bigInteger('reference_id')->unsigned();
            $table->foreign('reference_id')
                    ->references('reference_id')
                    ->on('references')
                    ->onDelete('cascade');
            $table->bigInteger('user_id')->unsigned();
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
        Schema::dropIfExists('suppliers');
    }
};
