<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportedProduct extends Model
{
    protected $guarded = ['id'];
 
    public function schedule(){
        return $this->belongsTo(CrmSchedule::class, 'crm_schedule_id');
    }

    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function control(){
        return $this->belongsTo(CrmScheduleControl::class, 'control_id');
    }

}
