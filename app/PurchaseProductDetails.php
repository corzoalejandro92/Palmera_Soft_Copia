<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseProductDetails extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    public function purchase ()
   {
      return $this->belongsTo(Purchase::class,'purchase_id');
   }

   public function product()
   {
      return $this->belongsTo(Product::class, 'product_id');
   }

   public function products ()
    {
        return $this->belongsTo(Product::class);
 
    }
}
