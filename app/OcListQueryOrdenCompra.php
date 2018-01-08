<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class OcListQueryOrdenCompra extends Pivot
{
    public $timestamps = false;
    //protected $fillable = ['name','oc_state_id'];

    /**
     * Get the state associated with the OcListQuery & OrdenCompra.
     */
    public function oc_state()
    {
        return $this->belongsTo('App\OcState');
    }
}