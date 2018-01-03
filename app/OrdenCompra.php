<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrdenCompra extends Model
{
    public $timestamps = false;
    protected $fillable = ['code'];

    /**
     * The OcListQuery's that contain the OrdenCompra
     */
    public function oc_list_queries()
    {
        return $this->belongsToMany('App\OcListQuery')
            ->withPivot('name')
            ->withPivot('oc_state_id')
        	->using('App\OcListQueryOrdenCompra');
    }

    /**
     * Convert null to empty string when setting code
     */
    public function setCodeAttribute($value) {
        $this->attributes['code'] = is_null($value) ? "" : $value;
    }
}
