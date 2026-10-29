<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function detailInventories()
    {
       return $this->hasMany(InventoryDetail::class);
    }

    public function detailInventory()
    {
       return $this->hasOne(InventoryDetail::class);
    }

    public function headquarter ()
    {
        return $this->belongsTo (Headquarter::class);
    }

    public function warehouse ()
    {
        return $this->belongsTo (Warehouse::class,'warehouse_id');
    }

    public function product(){
        return $this->belongsTo (Product::class,'product_id')->withTrashed();
    }
}
