<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailGeneralOrderPurchase extends Model
{

    protected $guarded = [];

    public function generalorderPurchase ()
    {
       return $this->belongsTo(GeneralOrderPurchase::class,'general_order_purchase_id');
    }

    public function product()
    {
       return $this->belongsTo(Product::class, 'product_id');
    }
}
