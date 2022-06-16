<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            //$table->string("name");
            //   $table->string("type");
            $table->boolean('is_active')->nullable()->default(1);
            $table->boolean('is_featured')->default(0);
            $table->string("model");
           // $table->string("warranty");
            $table->string("brand");
           // $table->string("function");
           // $table->string("application");
          //  $table->string("material");
          //  $table->string("effciency");
            $table->string("maximum_supply_voltage");
            $table->string("maximum_current_power");
          //  $table->string('description');
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
        Schema::dropIfExists('products');
    }
}
