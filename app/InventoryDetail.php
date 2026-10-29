<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryDetail extends Model
{
   use SoftDeletes;
   
    protected $guarded = ['id'];

    public function inventory ()
    {
       return $this->belongsTo(Inventory::class,'inventory_id')->withTrashed();
    }

    public function product()
    {
       return $this->belongsTo(Product::class, 'product_id');
    }
    
    public function foxconDocument()
    {
       return $this->belongsTo(FoxconDocument::class, 'foxcon_document_id');
    }

    public function warehouse()
    {
       return $this->belongsTo(Warehouse::class, 'warehouse_id');
    } 

}
