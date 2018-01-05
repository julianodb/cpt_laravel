<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\OcListQuery;
use App\OrdenCompra;
use App\OcState;
use DateTime;
use Facades\App\Util\MercadoPublico;

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
            ->with('oc_list_queries',OcListQuery::withCount('orden_compras')->get());
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
    	$result = MercadoPublico::get($req_url);
    	$json = json_decode($result);
    	$oc_list_query = OcListQuery::create([ 'date'=>$date,'query_date'=> new DateTime() ]);

    	$oc_states = OcState::all();

    	$all_oc = collect($json->Listado);
    	$all_oc = $all_oc->map(function($item, $key){ 
    		return ['code'=>$item->CodigoEstado,'name'=>$item->CodigoEstado]; });
    	foreach($all_oc->unique() as $oc) {
    		if($oc_states->where('code',$oc['code'])->where('name',$oc['name'])->count()==0) {
    			OcState::firstOrCreate([
    				'code' => $oc['code'], 
    				'name' => $oc['name']]);
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
									 $json->Cantidad.
									 ' items)');
    }

}
