<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\OrdenCompra;
use App\OcListQueryOrdenCompra;

class OrdenCompraCodeUnique extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // remove repeated codes in OrdenCompra
        foreach(OrdenCompra::query()->select('code')->groupBy('code')->havingRaw('count(code) > 1')->get() as $oc) {
            $surviving_oc = OrdenCompra::where('code',$oc->code)->first();
            foreach(OrdenCompra::where([
                ['code',$surviving_oc->code],
                ['id','<>',$surviving_oc->id]])->get() as $repeated_oc) {
                    foreach($repeated_oc->oc_list_queries as $oc_list_query){
                        $oc_list_query->orden_compras()->attach($surviving_oc->id,
                            ['name'=> $oc_list_query->pivot->name,
                             'oc_state_id'=> $oc_list_query->pivot->oc_state_id]);
                    }
                    $repeated_oc->oc_list_queries()->detach();
                    $repeated_oc->delete();
                }
        }
        Schema::table('orden_compras', function (Blueprint $table) {
            $table->unique('code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orden_compras', function (Blueprint $table) {
            $table->dropUnique('orden_compras_code_unique');
        });
    }
}
