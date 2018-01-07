<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OcPaymentType extends Model
{
    public $timestamps = false;
    protected $fillable = ['code', 'name'];

    /**
     * Get the OcItemQueries that belong to this payment_type.
     */
    public function oc_item_queries()
    {
        return $this->hasMany('App\OcItemQuery');
    }
}
