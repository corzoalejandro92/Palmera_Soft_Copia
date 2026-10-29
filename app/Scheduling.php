<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\GeneralScope;
use App\Scopes\ApiScope;
use App\General\DateTranslator;
use Jenssegers\Date\Date;

class Scheduling extends Model
{
    use SoftDeletes, GeneralScope, ApiScope, DateTranslator;
    
    protected $guarded = ['id'];
    protected $appends  = ['start_front','end_front','duration', 'duration_text'];
    
    public function crm(){
        return $this->belongsTo(Crm::class,'crm_id');
    }

    public function headquarter(){
        return $this->belongsTo(Headquarter::class,'headquarter_id');
    }

    public function office(){
       return $this->belongsTo(Office::class,'office_id'); 
    }
    
    public function contact(){
       return $this->belongsTo(Contact::class, 'contact_id');        
    }

    public function users(){
        return $this->belongsToMany(User::class);
    }
    
    public function schedulingType(){
        return $this->belongsTo(SchedulingType::class,'scheduling_type_id');
    }


    public function service(){
        return $this->hasOne(SchedulingService::class);
    }


    public function routes(){
        return $this->belongsToMany(Route::class,'route_schedulings');
    }

    public function userTracking(){
        return $this->morphOne(UserTracking::class, 'trackable');
    }

    public function userTrackings(){
        return $this->morphMany(UserTracking::class, 'trackable');
    }


    public function crmSchedule(){
        return $this->belongsTo(CrmSchedule::class, 'crm_schedule_id');
    }

    public function getStartFrontAttribute(){
        return new Date($this->start);
    }

    public function getEndFrontAttribute(){
        return new Date($this->end);
    }

    public function getDurationAttribute(){
        
        if($this->end){
            $start = \Carbon\Carbon::parse($this->start);
            $end = \Carbon\Carbon::parse($this->end);
            return $start->diffInMinutes($end);
        }else{
            return 60;
        }
        
    }

    public function getDurationTextAttribute(){

        $time = 0;
        if($this->end){
            $start = \Carbon\Carbon::parse($this->start);
            $end = \Carbon\Carbon::parse($this->end);
            $time = $start->diffInMinutes($end);
            $timeD = $time/60;
        }else{
            $time =  60;
            $timeD = $time/60;
        }

        $allTime = \Carbon\Carbon::createFromFormat('H:i:s', '00:00:00')->addMinutes($time);

        if($time >= 60){
            $format =  !is_float($timeD) != $timeD ? 'g \h\o\r\a\s i \m\i\n\u\t\o\s' : 'g \h\o\r\a\s';
        }else{
            $format =   'i \m\i\n\u\t\o\s';
        }
        

       return $allTime->format($format);
        
    }

    public function crmTracking(){
        return $this->morphOne(CrmTracking::class, 'trackable');
    }


    public function observations(){
        return $this->hasMany(Observation::class);
    }

    public function signature(){
        return $this->hasOne(Signature::class);
    }
    
    public function mechanismReports(){
        return $this->hasMany(MechanismReport::class, 'scheduling_id');
    }
    
}
