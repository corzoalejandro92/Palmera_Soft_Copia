<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\GeneralScope;

class PeakPlate extends Model
{
    use SoftDeletes,GeneralScope;

    protected $guarded = ['id'];
    
    public function city(){
        return $this->belongsTo(City::class,'city_id');
    }
    public function state(){
        return $this->belongsTo(State::class,'states_id');
    }
    public function rule(){
        return $this->belongsTo(ConditionsPeakPlates::class,'condition_id');
    }

    public function headquarter(){
        return $this->belongsTo(Headquarter::class,'headquarter_id');
    }

    public function vehicleType(){
        return $this->belongsTo(VehicleType::class,'vehicle_type_id');
    }
    public function getDigitsAttribute($value)
    {
        return json_decode($value);
    }
    public function getDateAttribute($value)
    {
        return \Jenssegers\Date\Date::parse($value)->locale('es_ES')->isoFormat('dddd DD-MM-YYYY');
    }

    
}
