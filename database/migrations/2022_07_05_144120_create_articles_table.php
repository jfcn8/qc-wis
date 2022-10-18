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

        Schema::create('classifications', function (Blueprint $table) {
            $table->id('classification_id');
            $table->string('classification');
            $table->timestamps();
        });

        Schema::create('articles', function (Blueprint $table) {
            $table->id('article_id');
            $table->string('article');
            $table->bigInteger('classification_id')->unsigned();
            $table->timestamps();
            $table->foreign('classification_id')
                  ->references('classification_id')
                  ->on('classifications')
                  ->onDelete('cascade');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
};
