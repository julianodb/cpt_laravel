<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OcListQuery extends Model
{
    public $timestamps = false;
    protected $fillable = ['date','query_date'];

    /**
     * The OrdenCompra's that belong to the OcListQuery.
     */
    public function orden_compras()
    {
        return $this->belongsToMany('App\OrdenCompra')
            ->withPivot('name')
            ->withPivot('oc_state_id')
        	->using('App\OcListQueryOrdenCompra');
    }
}