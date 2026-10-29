<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryExpense extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function control(){
        return $this->belongsTo(CrmScheduleControl::class, 'control_id');
    }

    public function schedule(){
        return $this->belongsTo(CrmSchedule::class, 'crm_schedule_id');
    }

    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function warehouse(){
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
    public function foxconDocument(){
        return $this->belongsTo(FoxconDocument::class, 'foxcon_document_id');
    }

    public function inventoryDetail(){
        return $this->belongsTo(InventoryDetail::class, 'inventory_detail_id')
        ->with(['inventory.product','product']);
    }

}
