<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cellar extends Model
{
    use SoftDeletes;
    
    protected $guarded = ['id'];
    
    public function warehouse(){
        return $this->morphOne(Warehouse::class, 'warehousable');
    }

    public function headquarter(){
        return $this->belongsTo(Headquarter::class, 'headquarter_id');
    }
}
