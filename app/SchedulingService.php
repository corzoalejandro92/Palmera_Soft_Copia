<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchedulingService extends Model
{
    protected $guarded = ['id'];
    
    public function schedulingSerObs(){
        return $this->hasMany(SchedulingSerObs::class);
    }

    public function schedulingSerLastObs(){
        return $this->hasOne(SchedulingSerObs::class);
    }
    
    public function schedulingSerSup(){
        return $this->hasMany(SchedulingSerSup::class);
    }
    
    public function schedulingSerTas(){
        return $this->hasMany(SchedulingSerTas::class);
    }
    
    public function service(){
        return $this->belongsTo(Service::class, 'service_id');
    }
    
    public function detailService(){
        return $this->belongsTo(DetailService::class, 'area_id');
    }

    public function userTracking(){
        return $this->morphOne(UserTracking::class, 'trackable');
    }

    public function quoteService(){
        return $this->belongsTo(QuoteService::class, 'quote_service_id');
    }

}
