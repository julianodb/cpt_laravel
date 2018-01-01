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

        Schema::create('oc_states',function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('code');
            $table->string('name',10);
        });

        Schema::create('oc_types',function(Blueprint $table) {
            $table->increments('id');
            $table->string('code',2);
            $table->string('name',50);
        });

        Schema::create('oc_delivery_types',function(Blueprint $table) {
            $table->increments('id');
            $table->string('code',2);
            $table->string('name',100);
        });

        Schema::create('oc_payment_types',function(Blueprint $table) {
            $table->increments('id');
            $table->string('code',2);
            $table->string('name',100);
        });

        Schema::create('oc_item_querys',function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('query_date');
            $table->unsignedBigInteger('orden_compra_id');
            $table->string('name',200);
            $table->string('description',500);
            $table->string('financing',100);
            $table->string('country',5);
            $table->float('classification_mean');
            $table->unsignedInteger('classification_n');
            $table->unsignedInteger('oc_state_id');
            $table->unsignedInteger('oc_type_id');
            $table->unsignedInteger('oc_delivery_type_id');
            $table->unsignedInteger('oc_payment_type_id');
            $table->dateTime('created_at');
            $table->dateTime('sent_at');
            $table->dateTime('accepted_at');
            $table->dateTime('cancelled_at');
            $table->dateTime('updated_at');

            $table->foreign('orden_compra_id')
                ->references('id')->on('orden_compras')
                ->onDelete('restrict');

            $table->foreign('oc_state_id')
                ->references('id')->on('oc_states')
                ->onDelete('restrict');

            $table->foreign('oc_type_id')
                ->references('id')->on('oc_types')
                ->onDelete('restrict');

            $table->foreign('oc_delivery_type_id')
                ->references('id')->on('oc_delivery_types')
                ->onDelete('restrict');

            $table->foreign('oc_payment_type_id')
                ->references('id')->on('oc_payment_types')
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
        Schema::drop('oc_item_querys');
        Schema::drop('orden_compras');
        Schema::drop('oc_states');
        Schema::drop('oc_types');
        Schema::drop('oc_delivery_types');
        Schema::drop('oc_payment_types');
    }
}
