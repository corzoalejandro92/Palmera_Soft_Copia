<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderPurchaseDetail extends Model
{
    protected $guarded = [];
    protected $appends = ['quantity_transfer'];
    use SoftDeletes;
    public function orderPurchase ()
    {
       return $this->belongsTo(OrderPurchase::class,'order_purchase_id');
    }

    public function product()
   {
      return $this->belongsTo(Product::class, 'product_id');
   }

   public function getQuantityTransferAttribute(){
     return $this->quantity;
   } 
}
