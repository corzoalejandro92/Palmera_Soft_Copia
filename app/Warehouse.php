<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use SoftDeletes;
    
    protected $casts = [
        'buttons' => 'array'
    ];
    protected $guarded = ['id'];


    public function warehousable()
    {
        return $this->morphTo();
    }

    public function purchases ()
    {
        return $this->hasMany(Purchase::class,'warehouse_id');
    }

    public function transferInventory ()
    {
        return $this->hasMany(TransferInventory::class,'warehouse_id');
    }

    public function InventoryTransfer ()
    {
        return $this->hasMany(TransferInventory::class);
    }

    public function productConfig ()
    {
        return $this->hasMany(ProductWarehouseConfig::class);
    }

    public function orderPurchases ()
    {
        return $this->hasMany(OrderPurchase::class);
    }

    public function inventory (){
        return $this->hasMany(Inventory::class)->with('product');
    }

    public function parent(){
        return $this->belongsTo(Warehouse::class, 'parent');
    }
    public function parentt(){
        return $this->belongsTo(Warehouse::class, 'parent');
    }
    public function configurationFoxcon(){
        return $this->hasMany(ConfigurarionHeadquarterFoxcon::class, 'warehouse_id');
    }

    public function giftInventory(){
        return $this->hasMany(GiftInventory::class, 'warehouse_id');
    }

}
