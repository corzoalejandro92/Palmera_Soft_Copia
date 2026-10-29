<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\GeneralScope;

class Vehicle extends Model
{
    use SoftDeletes, GeneralScope;
    protected $guarded = [];

    public function vehicleTypes ()
   {
      return $this->belongsTo(VehicleType::class,'vehicle_type_id');
   }

   public function headquarter ()
   {
      return $this->belongsTo(Headquarter::class,'headquarter_id');
   }

   public function files ()
   {
      return $this->morphMany(File::class, 'filable');
   }

   public function file ()
   {
      return $this->morphMany(File::class, 'filable');
   }


   public function users ()
   {
      return $this->belongsTo(User::class,'user_id');
   }

}
