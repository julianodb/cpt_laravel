<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\OcItemQuery;
use App\OrdenCompra;
use App\OcState;
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
        $validation_rules = ['partial'=> 'required|string|max:500'];
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

        return redirect()
        	->route('oc_item_queries')
        	->with('success-message','Item de Órden de Compra con código '.
									 $request->request->get('code').
									 ' adicionado con éxito');
    }

}
