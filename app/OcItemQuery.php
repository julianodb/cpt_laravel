<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OcItemQuery extends Model
{
    public $timestamps = false;
    protected $fillable = [
            'query_date',
            'orden_compra_id',
            'name',
            'oc_state_id',
            'description',
            'oc_type_id',
            'created_at',
            'sent_at',
            'accepted_at',
            'cancelled_at',
            'updated_at',
            'classification_mean',
            'classification_n',
            'financing',
            'country',
            'oc_delivery_type_id',
            'oc_payment_type_id'
    ];
    protected $dates = ['query_date','created_at','sent_at','accepted_at','cancelled_at','updated_at'];

    /**
     * Get the OrdenCompra associated with the OcItemQuery.
     */
    public function orden_compra()
    {
        return $this->belongsTo('App\OrdenCompra');
    }

    /**
     * Get the state associated with the OcItemQuery.
     */
    public function oc_state()
    {
        return $this->belongsTo('App\OcState');
    }

    /**
     * Get the type associated with the OcItemQuery.
     */
    public function oc_type()
    {
        return $this->belongsTo('App\OcType');
    }

    /**
     * Get the delivery_type associated with the OcItemQuery.
     */
    public function oc_delivery_type()
    {
        return $this->belongsTo('App\OcDeliveryType');
    }

    /**
     * Get the payment_type associated with the OcItemQuery.
     */
    public function oc_payment_type()
    {
        return $this->belongsTo('App\OcPaymentType');
    }
}
