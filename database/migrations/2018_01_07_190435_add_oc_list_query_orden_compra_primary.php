<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\OcState;
use App\OcType;
use App\OcDeliveryType;
use App\OcPaymentType;

class AddOcListQueryOrdenCompraPrimary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('oc_list_query_orden_compra', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unique(['oc_list_query_id', 'orden_compra_id'],
                'oc_list_query_id_orden_compra_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('oc_list_query_orden_compra', function (Blueprint $table) {
            $table->dropColumn('id');
            $table->dropUnique('oc_list_query_id_orden_compra_id_unique');
        });
    }
}
