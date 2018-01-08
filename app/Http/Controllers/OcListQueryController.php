<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\OcListQuery;
use App\OrdenCompra;
use App\OcState;
use DateTime;
use Facades\App\Util\MercadoPublico;
use Validator;

class OcListQueryController extends Controller
{
    /**
     * Display a listing of oc_list_queries.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('oc_list_queries.index')
        	->with('title','Lista de Órdenes de Compra')
            ->with('oc_list_queries',
            	OcListQuery::withCount('orden_compras')
            		->orderBy('date','desc')
            		->limit(20)
            		->get());
    }

    /**
     * Stores a new oc_list_query (and corresponding CompraPublica)
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	$validation_rules = ['date'=> 'date|before_or_equal:today'];
    	$validation_messages = [
    		'date.date'=> ':attribute no es una fecha válida.',
    		'date.before_or_equal' => ':attribute debe estar en el pasado.'];
		$validator = Validator::make($request->all(), $validation_rules, $validation_messages);

        if ($validator->fails()) {
            return redirect('oc_list_queries')
                        ->withErrors($validator)
                        ->withInput();
        }
    	$ticket = '34EA724F-17C8-462E-B23B-4A92B3A2F622';
    	//'F8537A18-6766-4DEF-9E59-426B4FEE2844'

    	$date = DateTime::createFromFormat('d-m-Y',$request->request->get('date'));
    	$req_url = 'http://api.mercadopublico.cl/servicios/v1/publico/';
 		$req_url .= 'ordenesdecompra.json';
 		$req_url .= '?fecha='.$date->format('dmY');
 		$req_url .= '&ticket='.$ticket;
    	$result = MercadoPublico::get($req_url);
    	$json = json_decode($result);

    	if(property_exists($json, 'Codigo') and property_exists($json, 'Mensaje')) {
    		return redirect('oc_list_queries')
	        	->withErrors([$json->Codigo=> $json->Mensaje]);
    	}

    	$oc_list_query = OcListQuery::create([ 'date'=> $date,'query_date'=> new DateTime() ]);

    	$oc_states = OcState::all();

    	if(!property_exists($json, 'Listado')){
    		return redirect('oc_list_queries')
	        	->withErrors(['empty'=> 'Lista vacia o error encontrado']);
    	}

    	$all_oc = collect($json->Listado);
    	$all_oc = $all_oc->map(function($item, $key){ 
    		return ['code'=>$item->CodigoEstado,'name'=>$item->CodigoEstado]; });
    	foreach($all_oc->unique() as $oc) {
    		if($oc_states->where('code',$oc['code'])->where('name',$oc['name'])->count()==0) {
    			OcState::firstOrCreate(['code' => $oc['code']],
                    ['name' => $oc['name']]);
    			$oc_states = OcState::all();
    		}
    	}

    	$ocs = [];
    	$oc_attr = [];

    	foreach($json->Listado as $oc) {
    		$ocs[] = OrdenCompra::firstOrNew(['code' => $oc->Codigo]);
    		$oc_attr[] = [
    			'name' => $oc->Nombre,
    			'oc_state_id' => $oc_states->where('code',$oc->CodigoEstado)
    				->where('name',$oc->CodigoEstado)
    				->first()
    				->id];
    	}
    	
    	$oc_list_query->orden_compras()->saveMany($ocs,$oc_attr);

        return redirect()
        	->route('oc_list_queries')
        	->with('success-message','Lista de Órdenes de Compra para el '.
									 $request->request->get('date').
									 ' adicionada con éxito. ('.
									 count($json->Listado).
									 ' items)');
    }

}
