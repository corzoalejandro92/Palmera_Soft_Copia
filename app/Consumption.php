<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Consumption extends Model
{

    use SoftDeletes;
    protected $guarded = ['id'];


    public function schedule(){
        return $this->belongsTo(CrmSchedule::class, 'crm_schedule_id');
    }
    public function users(){
        return $this->belongsToMany(User::class);
    }

    public function foxconDocument(){
        return $this->belongsTo(FoxconDocument::class, 'foxcon_document_id');
    }

    public function inventoryDetail(){
        return $this->belongsTo(InventoryDetail::class, 'inventory_detail_id')->withTrashed();
    }


}


