<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SharedMechasnims extends Model
{
    protected $guarded = ['id'];

    public function warehouse(){
        return $this->morphOne(Warehouse::class, 'warehousable');
    }
}
