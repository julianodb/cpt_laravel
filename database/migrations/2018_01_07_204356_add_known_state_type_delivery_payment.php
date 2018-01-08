<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\OcState;
use App\OcType;
use App\OcDeliveryType;
use App\OcPaymentType;

class AddKnownStateTypeDeliveryPayment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $oc_states = [
            ['code'=> '4','name'=> 'Enviada a Proveedor'],
            ['code'=> '6','name'=> 'Aceptada'],
            ['code'=> '9','name'=> 'Cancelada'],
            ['code'=> '12','name'=> 'Recepción Conforme'],
            ['code'=> '13','name'=> 'Pendiente de Recepcionar'],
            ['code'=> '14','name'=> 'Recepcionada Parcialmente'],
            ['code'=> '15','name'=> 'Recepcion Conforme Incompleta']
        ];
        foreach($oc_states as $oc_state){
            $new = OcState::firstOrCreate($oc_state);
            foreach(OcState::where('code',$new->code)
                ->where('name','<>',$new->name)
                ->get() as $old) {
                    foreach($old->oc_item_queries as $oc_item) {
                        $oc_item->oc_state()->associate($new);
                        $oc_item->save();
                    }
                    foreach($old->oc_list_query_orden_compras as $oc_list_oc) {
                        $oc_list_oc->oc_state()->associate($new);
                        $oc_list_oc->save();
                    }
                    $old->delete();
            }
        }

        $oc_types = [
            ['code'=> 'OC', 'name'=> 'Automatica'],
            ['code'=> 'D1', 'name'=> 'Trato directo que genera Orden de Compra por proveedor único'],
            ['code'=> 'C1', 'name'=> 'Trato directo que genera Orden de Compra por emergencia, urgencia e imprevisto'],
            ['code'=> 'F3', 'name'=> 'Trato directo que genera Orden de Compra por confidencialidad'],
            ['code'=> 'G1', 'name'=> 'Trato directo que genera Orden de Compra por naturaleza de negociación'],
            ['code'=> 'R1', 'name'=> 'Orden de compra menor a 3UTM'],
            ['code'=> 'CA', 'name'=> 'Orden de compra sin resolución.'],
            ['code'=> 'SE', 'name'=> 'Sin emisión automática'],
            ['code'=> 'CM', 'name'=> 'Convenio Marco'],
            ['code'=> 'FG', 'name'=> 'FG'],
            ['code'=> 'TL', 'name'=> 'TL']
        ];

        Schema::table('oc_types', function (Blueprint $table) {
            $table->string('name',100)->change();
        });
        foreach($oc_types as $oc_type){
            $new = OcType::firstOrCreate($oc_type);
            foreach(OcType::where('code',$new->code)
                ->where('name','<>',$new->name)
                ->get() as $old) {
                    foreach($old->oc_item_queries as $oc_item) {
                        $oc_item->oc_type()->associate($new);
                        $oc_item->save();
                    }
                    $old->delete();
            }
        }

        $oc_delivery_types = [
            ['code'=>7, 'name'=> 'Despachar a Dirección de envío'],
            ['code'=>9, 'name'=> 'Despachar según programa adjuntado'],
            ['code'=>12, 'name'=> 'Otra Forma de Despacho, Ver Instruc'],
            ['code'=>14, 'name'=> 'Retiramos de su bodega'],
            ['code'=>20, 'name'=> 'Despacho por courier o encomienda aérea'],
            ['code'=>21, 'name'=> 'Despacho por courier o encomienda terrestre'],
            ['code'=>22, 'name'=> 'A convenir']
        ];
        foreach($oc_delivery_types as $oc_delivery_type){
            $new = OcDeliveryType::firstOrCreate($oc_delivery_type);
            foreach(OcDeliveryType::where('code',$new->code)
                ->where('name','<>',$new->name)
                ->get() as $old) {
                    foreach($old->oc_item_queries as $oc_item) {
                        $oc_item->oc_delivery_type()->associate($new);
                        $oc_item->save();
                    }
                    $old->delete();
            }
        }

        $oc_payment_types = [
            ['code'=>1, 'name'=> '15 días contra la recepción de la factura'],
            ['code'=>2, 'name'=> '30 días contra la recepción de la factura'],
            ['code'=>39, 'name'=> 'Otra forma de pago'],
            ['code'=>46, 'name'=> '50 días contra la recepción de la factura'],
            ['code'=>47, 'name'=> '60 días contra la recepción de la factura'],
        ];
        foreach($oc_payment_types as $oc_payment_type){
            $new = OcPaymentType::firstOrCreate($oc_payment_type);
            foreach(OcPaymentType::where('code',$new->code)
                ->where('name','<>',$new->name)
                ->get() as $old) {
                    foreach($old->oc_item_queries as $oc_item) {
                        $oc_item->oc_payment_type()->associate($new);
                        $oc_item->save();
                    }
                    $old->delete();
            }
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach(OcTypes::whereRaw('CHAR_LENGTH(code) > 50') as $oc_type) {
            $oc_type->oc_item_queries()->detach();
            $oc_type->delete();
        }

        Schema::table('oc_types', function (Blueprint $table) {
            $table->string('name',50)->change();
        });
    }
}
