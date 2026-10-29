<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductWarehouseConfig extends Model
{
   use SoftDeletes;
    protected $guarded = [];

    public function product ()
   {
      return $this->belongsTo(Product::class,'product_id');
   }

   public function warehouse ()
   {
       return $this->belongsTo (Warehouse::class,'warehouse_id');
   }
}
