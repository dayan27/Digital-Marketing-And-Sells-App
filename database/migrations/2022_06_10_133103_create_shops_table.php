<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            // $table->string('shop_name');
            // $table->string('region');
            // $table->string('zone');
            // $table->string('woreda');
            $table->string('kebele');
           // $table->string('city');
            $table->string('latitude');
            $table->string('longitude');
            $table->boolean('is_active')->default(1);
            $table->foreignId('manager_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('shops');
    }
}
