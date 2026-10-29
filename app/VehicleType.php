<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VehicleType extends Model
{
    protected $guarded = [];

    public function vehicle ()
   {
      return $this-> hasOne (Vehicle::class);
   }

}
