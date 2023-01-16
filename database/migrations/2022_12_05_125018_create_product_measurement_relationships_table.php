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
        Schema::create('product_measurement_relationships', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->integer('product_attribute_value_id')->default(0); // //aka products_variants id
            $table->unsignedBigInteger('measurement_id');
            $table->string('value');
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
        Schema::dropIfExists('product_lengths');
    }
};
