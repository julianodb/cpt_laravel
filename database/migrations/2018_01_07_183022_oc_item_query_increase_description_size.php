<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\OcItemQuery;

class OcItemQueryIncreaseDescriptionSize extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('oc_item_queries', function (Blueprint $table) {
            $table->renameColumn('description','description_old');
        });
        Schema::table('oc_item_queries', function (Blueprint $table) {
            $table->text('description');
        });
        foreach(OcItemQuery::all() as $oc_item){
            $oc_item->description = $oc_item->description_old;
            $oc_item->save();
        }
        Schema::table('oc_item_queries', function (Blueprint $table) {
            $table->dropColumn('description_old');
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
            $table->renameColumn('description','description_old');
        });
        Schema::table('oc_item_queries', function (Blueprint $table) {
            $table->string('description',500);
        });
        foreach(OcItemQuery::all() as $oc_item){
            $oc_item->description = $oc_item->description_old;
            $oc_item->save();
        }
        Schema::table('oc_item_queries', function (Blueprint $table) {
            $table->dropColumn('description_old');
        });
    }
}
