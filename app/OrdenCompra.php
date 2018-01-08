<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrdenCompra extends Model
{
    public $timestamps = false;
    protected $fillable = ['code'];

    /**
     * The OcListQueries that contain the OrdenCompra
     */
    public function oc_list_queries()
    {
        return $this->belongsToMany('App\OcListQuery')
            ->withPivot('name')
            ->withPivot('oc_state_id')
            ->using('App\OcListQueryOrdenCompra');
    }

    /**
     * The OcItemQueries that contain the OrdenCompra
     */
    public function oc_item_queries()
    {
        return $this->hasMany('App\OcItemQuery');
    }

    /**
     * Convert null to empty string when setting code
     */
    public function setCodeAttribute($value) {
        $this->attributes['code'] = is_null($value) ? "" : $value;
    }
}
