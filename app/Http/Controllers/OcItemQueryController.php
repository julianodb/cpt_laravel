<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\OcItemQuery;
use App\OrdenCompra;
use App\OcState;
use App\OcType;
use App\OcPaymentType;
use App\OcDeliveryType;
use DateTime;
use Facades\App\Util\MercadoPublico;
use Validator;

class OcItemQueryController extends Controller
{
    /**
     * Display a listing of oc_item_queries.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('oc_item_queries.index')
        	->with('title','Items de Órdenes de Compra')
            ->with('oc_item_queries',
            	OcItemQuery::query()
            		->orderBy('query_date','desc')
            		->limit(100)
            		->get());
    }

    /**
     * Suggests OrdenCompra codes
     *
     * @return \Illuminate\Http\Response
     */
    public function suggest(Request $request)
    {
        $validation_rules = ['partial'=> 'required|string|max:50'];
        $validator = Validator::make($request->all(), $validation_rules);

        if ($validator->fails()) {
            return response()->json(OrdenCompra::query()
                    ->limit(5)
                    ->get()
                    ->map(function($oc){return $oc->code;}));
        }
        return response()->json(OrdenCompra::query()
                ->where('code','like','%'.$request->request->get('partial').'%')
                ->limit(5)
                ->get()
                ->map(function($oc){return $oc->code;}));
    }

    /**
     * Stores a new oc_item_query (and corresponding CompraPublica)
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	/*$validation_rules = ['date'=> 'date|before_or_equal:today'];
    	$validation_messages = [
    		'date.date'=> ':attribute no es una fecha válida.',
    		'date.before_or_equal' => ':attribute debe estar en el pasado.'];
		$validator = Validator::make($request->all(), $validation_rules, $validation_messages);

        if ($validator->fails()) {
            return redirect('oc_list_queries')
                        ->withErrors($validator)
                        ->withInput();
        }*/
    	$ticket = '34EA724F-17C8-462E-B23B-4A92B3A2F622';
    	//'F8537A18-6766-4DEF-9E59-426B4FEE2844'

    	$code = $request->request->get('code');
    	$req_url = 'http://api.mercadopublico.cl/servicios/v1/publico/';
 		$req_url .= 'ordenesdecompra.json';
 		$req_url .= '?codigo='.$code;
 		$req_url .= '&ticket='.$ticket;
    	$result = MercadoPublico::get($req_url);
    	$json = json_decode($result);

        if(property_exists($json, 'Codigo') and property_exists($json, 'Mensaje')) {
            return redirect('oc_item_queries')
                ->withErrors([$json->Codigo=> $json->Mensaje]);
        }

        if(!property_exists($json, 'Listado')){
            return redirect('oc_item_queries')
                ->withErrors(['empty'=> 'Lista vacia o error encontrado']);
        }

        $oc_item = $json->Listado[0];

        $required_properties = ['Codigo','CodigoEstado','Estado','CodigoTipo',
            'Tipo','TipoDespacho','FormaPago','Nombre','Descripcion',
            'TipoMoneda','Fechas','PromedioCalificacion','CantidadEvaluacion',
            'Financiamiento','Pais'];

        $errors = [];
        foreach($required_properties as $p){
            if(!property_exists($oc_item, $p)){
                $errors[] = ['missing '.$p=> 'Objeto obtenido no contiene propiedad "'.$p.'"'];
            }
        }
        if(count($errors) > 0){
            return redirect('oc_item_queries')
                ->withErrors($errors);
        }

        $oc = OrdenCompra::firstOrCreate(['code'=> $oc_item->Codigo]);

        $oc_state = OcState::firstOrCreate([
            'code'=> $oc_item->CodigoEstado,
            'name'=> $oc_item->Estado]);
        
        $oc_type = OcType::firstOrCreate([
            'code'=> $oc_item->CodigoTipo,
            'name'=> $oc_item->Tipo]);
        
        $oc_delivery_type = OcDeliveryType::firstOrCreate(['code'=> $oc_item->TipoDespacho],
            ['name'=> $oc_item->TipoDespacho]);
        
        $oc_payment_type = OcPaymentType::firstOrCreate(['code'=> $oc_item->FormaPago],
            ['name'=> $oc_item->FormaPago]);

        $oc_item_query = OcItemQuery::create([
            'query_date'=> new DateTime(),
            'orden_compra_id'=> $oc->id,
            'name'=> $oc_item->Nombre,
            'oc_state_id'=> $oc_state->id,
            'description'=> $oc_item->Descripcion,
            'oc_type_id'=> $oc_type->id,
            'created_at'=> $oc_item->Fechas->FechaCreacion ? 
                DateTime::createFromFormat('Y-m-d\TH:i:s.u',$oc_item->Fechas->FechaCreacion) : null,
            'sent_at'=> $oc_item->Fechas->FechaEnvio ? 
                DateTime::createFromFormat('Y-m-d\TH:i:s.u',$oc_item->Fechas->FechaEnvio) : null,
            'accepted_at'=> $oc_item->Fechas->FechaAceptacion ?
                DateTime::createFromFormat('Y-m-d\TH:i:s.u',$oc_item->Fechas->FechaAceptacion) : null,
            'cancelled_at'=> $oc_item->Fechas->FechaCancelacion ? 
                DateTime::createFromFormat('Y-m-d\TH:i:s.u',$oc_item->Fechas->FechaCancelacion) : null,
            'updated_at'=> $oc_item->Fechas->FechaUltimaModificacion ?
                DateTime::createFromFormat('Y-m-d\TH:i:s',
                    $oc_item->Fechas->FechaUltimaModificacion) : null,
            'classification_mean'=> $oc_item->PromedioCalificacion,
            'classification_n'=> $oc_item->CantidadEvaluacion,
            'financing'=> $oc_item->Financiamiento,
            'country'=> $oc_item->Pais,
            'oc_delivery_type_id'=> $oc_delivery_type->id,
            'oc_payment_type_id'=> $oc_payment_type->id ]);

        return redirect()
        	->route('oc_item_queries')
        	->with('success-message','Item de Órden de Compra con código '.
									 $request->request->get('code').
									 ' adicionado con éxito');
    }

}
