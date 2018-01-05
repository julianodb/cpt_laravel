<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ImprovesOcItemQuery extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('oc_item_querys','oc_item_queries');

        Schema::table('oc_item_queries', function (Blueprint $table) {
            $table->string('name', 500)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('oc_item_queries', function (Blueprint $table) {
            $table->string('name', 200)->change();
        });

        Schema::rename('oc_item_queries','oc_item_querys');
    }
}
