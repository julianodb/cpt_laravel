<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OcState extends Model
{
    public $timestamps = false;
    protected $fillable = ['code', 'name'];

    /**
     * Get the OcListQuery & OrdenCompra that belong to this state.
     */
    public function oc_list_query_orden_compras()
    {
        return $this->hasMany('App\OcListQueryOrdenCompra');
    }

    /**
     * Get the OcItemQueries that belong to this state.
     */
    public function oc_item_queries()
    {
        return $this->hasMany('App\OcItemQuery');
    }
}
