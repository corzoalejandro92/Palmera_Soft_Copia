<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchedulingSerSup extends Model
{
    protected $table = 'scheduling_service_supplies';
    protected $guarded = ['id'];
    
    public function product(){
       return $this->belongsTo(Product::class,'product_id'); 
    }
}
