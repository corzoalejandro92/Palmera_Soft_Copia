<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CrmScheduleControlProducts extends Model
{

    protected $table = 'crm_sch_con_pro';
    protected $guarded = ['id'];
    protected $with = ['productable'];

    public function productable(){
        return $this->morphTo();
    }

    public function control(){
        return $this->belongsTo(QuoteSerInf::class,'crm_schedule_control_id');
    }

    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }

    public function application(){
        return $this->belongsTo(Application::class,'application_id');
    }

    public function area(){
        return $this->belongsTo(CrmArea::class,'crm_area_id');
    }

}
