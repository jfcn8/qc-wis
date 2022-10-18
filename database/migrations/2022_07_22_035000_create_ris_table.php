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
        Schema::create('ris', function (Blueprint $table) {
            $table->id('ris_id');
            $table->string('ris_no');
            $table->date('date_request');
            $table->string('purpose');
            $table->integer('user_id')->unsigned();
            $table->integer('office_id')->unsigned();
            $table->boolean('gso')->nullable()->default('0');
            $table->boolean('budget')->nullable()->default('0');
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
        Schema::dropIfExists('ris');
    }
};
