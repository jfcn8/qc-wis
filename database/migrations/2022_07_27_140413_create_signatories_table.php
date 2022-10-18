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
        Schema::create('signatories', function (Blueprint $table) {
            $table->id('signatory_id');
            $table->string('name');
            $table->string('designation');
            $table->boolean('mism_certified')->nullable()->default('0');
            $table->boolean('mism_approved')->nullable()->default('0');
            $table->boolean('ssmi_noting')->nullable()->default('0');
            $table->boolean('ssmi_certifying')->nullable()->default('0');
            $table->boolean('ssmi_approving')->nullable()->default('0');
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
        Schema::dropIfExists('signatories');
    }
};
