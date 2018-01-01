<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOcListQuery extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oc_list_queries', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->dateTime('query_date');
            $table->date('date');
        });

        Schema::create('oc_list_query_orden_compra', function(Blueprint $table){
            $table->unsignedBigInteger('oc_list_query_id');
            $table->unsignedBigInteger('orden_compra_id');
            $table->string('name',500);
            $table->unsignedInteger('oc_state_id');

            $table->foreign('oc_list_query_id')
                ->references('id')->on('oc_list_queries')
                ->onDelete('restrict');

            $table->foreign('orden_compra_id')
                ->references('id')->on('orden_compras')
                ->onDelete('restrict');

            $table->foreign('oc_state_id')
                ->references('id')->on('oc_states')
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
        Schema::drop('oc_list_query_orden_compra');
        Schema::drop('oc_list_queries');
    }
}
