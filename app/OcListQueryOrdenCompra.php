<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class OcListQueryOrdenCompra extends Pivot
{
    /**
     * Get the state associated with the OcListQuery & OrdenCompra.
     */
    public function oc_state()
    {
        return $this->belongsTo('App\OcState');
    }
}