<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\OcListQuery;
use App\OrdenCompra;
use App\OcState;
use DateTime;

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
        	->with('title','Orden de Compra List Queries')
            ->with('oc_list_queries',OcListQuery::all());
    }

    /**
     * Stores a new oc_list_query (and corresponding CompraPublica)
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	$ticket = '34EA724F-17C8-462E-B23B-4A92B3A2F622';
    	//'F8537A18-6766-4DEF-9E59-426B4FEE2844'

    	$date = DateTime::createFromFormat('d/m/Y',$request->request->get('date'));
    	$req_url = 'http://api.mercadopublico.cl/servicios/v1/publico/';
 		$req_url .= 'ordenesdecompra.json';
 		$req_url .= '?fecha='.$date->format('dmY');
 		$req_url .= '&ticket='.$ticket;
    	$result = file_get_contents($req_url);
    	$json = json_decode($result);
    	$oc_list_query = OcListQuery::create([ 'date'=>$date,'query_date'=> new DateTime() ]);
    	foreach($json->Listado as $oc) {
    		$new_oc = OrdenCompra::firstOrCreate(['code' => $oc->Codigo]);
    		$oc_state = OcState::firstOrCreate(['code' => $oc->CodigoEstado, 'name' => $oc->CodigoEstado]);
    		$oc_list_query->orden_compras()->attach($new_oc, [
    			'name'=>$oc->Nombre, 
    			'oc_state_id'=>$oc_state->id]);
    	}
        return redirect()->route('oc_list_queries')->with('message',$json->Cantidad);
    }

}
