<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\OcListQuery;

class OcListQueryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('oc_list_queries.index')
        	->with('title','Orden de Compra List Queries')
            ->with('oc_list_queries',OcListQuery::all());
    }

}
