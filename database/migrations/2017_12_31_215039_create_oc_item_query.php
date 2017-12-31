<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOcItemQuery extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orden_compras',function(BLueprint $table){
            $table->bigIncrements('id');
            $table->string('code',50);
        });

        Schema::create('oc_item_querys',function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('query_date');
            $table->unsignedBigInteger('orden_compra_id');

            $table->foreign('orden_compra_id')
                ->references('id')->on('orden_compras')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('orden_compras');
        Schema::drop('oc_item_querys');
    }
}
