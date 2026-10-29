<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransferInventoryDetail extends Model
{
   use SoftDeletes;
   protected $guarded = [];
    public function transferInventory ()
    {
       return $this->belongsTo(TransferInventory::class,'inventory_id');
    }

    public function product()
    {
       return $this->belongsTo(Product::class, 'product_id');
    }
}
