<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product', function (Blueprint $table) {
            $table->id();
            $table->string("name");
         //   $table->string("type");
            $table->string("model");
            $table->string("warranty");
            $table->string("brand");
            $table->string("function");
            $table->string("application");
            $table->string("material");
            $table->string("effciency");
            $table->string("maxiumum_supply_voltage");
            $table->string("maxiumum_current_power");
            $table->double("price");
            $table->integer("qty");
            $table->double("weight");
            $table->date("date_of_production");
            $table->foreignId('category_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('product');
    }
}
