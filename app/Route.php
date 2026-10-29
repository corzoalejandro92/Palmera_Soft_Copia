<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use App\Scopes\ApiScope;
use App\Scopes\GeneralScope;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditingAuditable;
class Route extends Model implements Auditable
{
    use SoftDeletes, GeneralScope, ApiScope,AuditingAuditable;

    protected $guarded = ['id'];
    protected $with = ['user','automation.user','status'];
    protected $withCount = ['schedulings'];
    protected $appends = ['progress','progress_per','times','status_class','url_file'];

    public $_times = null;

    public function userTracking(){
        return $this->morphOne(UserTracking::class, 'trackable');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function workingHours(){
        return $this->hasOne(UserWorkingHour::class,'route_id')->withTrashed();
    }

    public function schedulings(){
        return $this->belongsToMany(Scheduling::class,'route_schedulings','route_id')->orderBy('start','ASC');
    }

    public function day_schedulings() {
        return $this->belongsToMany(Scheduling::class, 'day_scheduling_user_route', 'route_id');
    }

    public function automation(){
        return $this->belongsTo(Automation::class, 'automation_id')->withTrashed();
    }

    public function mailqueue(){
        return $this->belongsTo(Job::class,'mail_queue');
    }

    public function smsqueue(){
        return $this->belongsTo(Job::class,'sms_queue');
    }

    public function status(){
        return $this->belongsTo(RouteStatus::class, 'status_id');
    }

    public function getProgressAttribute(){
        return $this->schedulings->where('completed', 1)->count();
    }

    public function getProgressPerAttribute(){
        $items = $this->schedulings->count();
        $completed = $this->schedulings->where('completed', 1)->count();
        try{
            $progress = number_format(($completed * 100) / $items,0); 
        }catch(\Exception $ex){
            $progress = 0; 
        }
        return $progress;
    }

    public function getStatusClassAttribute(){
        $class = '';
        switch ($this->status_id) {
            case 1:
                $class = 'warning';
                break;
            case 2:
                $class = 'secondary';
                break;
            case 3:
                $class = 'success';
                break;
            case 4:
                $class = 'danger';
                break;
            default:
                $class = 'light';
                break;
        }
        return $class;
    }
   

    public function getTimesAttribute(){
        if ($this->_times === null) {
            $schedulings = $this->day_schedulings->map(function($item){
                if($item->end){
                    $item->duration = \Carbon\Carbon::parse($item->start)->diffInMinutes( \Carbon\Carbon::parse($item->end));
                }else{
                    $item->duration = 59;
                }
                return $item;
            });
    
            $duration = $schedulings->sum('duration')/60;
            $minutesDuration = $schedulings->sum('duration');
    
    
            $totalDuration = $minutesDuration;
            $allTime = \Carbon\Carbon::createFromFormat('H:i:s', '00:00:00')->addMinutes($totalDuration );
    
            if($totalDuration >720){
                $total_time_humans_foormat =  !is_float($duration) != $duration ? '\1 \d\i\a g \h\o\r\a\s i \m\i\n\u\t\o\s' : '\1 \d\i\a g \h\o\r\a\s';
            }else{
                $total_time_humans_foormat =  !is_float($duration) != $duration ? 'g \h\o\r\a\s i \m\i\n\u\t\o\s' : 'g \h\o\r\a\s';
            }
           
    
            $ordiaryDuration = $minutesDuration > 480 ? 480 : $minutesDuration;
            $ordiaryDurationD = $duration > 8 ? 8 : $duration;
            $someTime = \Carbon\Carbon::createFromFormat('H:i:s', '00:00:00')->addMinutes($ordiaryDuration);
    
            $ordnary_time_humans_foormat =  !is_float($ordiaryDurationD) != $ordiaryDurationD ? 'g \h\o\r\a\s i \m\i\n\u\t\o\s' : 'g \h\o\r\a\s';
            
            $extraTime = 0;
            $extraDuration = ($minutesDuration < 480) ?  0 : ($minutesDuration - 480);
            $extraDurationD  = $duration - 8;
            if($extraDuration > 0){
                $someTimeExtra = \Carbon\Carbon::createFromFormat('H:i:s', '00:00:00')->addMinutes($extraDuration);
            }else{
                $someTimeExtra = 0;
            }
            if($extraDuration < 60){
                $extra_time_humans_format =   'i \m\i\n\u\t\o\s';
            }else{
                $extra_time_humans_format =  !is_float($extraDurationD) != ($extraDurationD )? 'g \h\o\r\a\s i \m\i\n\u\t\o\s' : 'g \h\o\r\a\s';
            }
           
    
            $data = [
                "duration"             => $duration,
                "duration_humans"      => $allTime->format($total_time_humans_foormat),
                "ordnary_time"         => $duration > 8 ?  8 : $duration,
                "extra_time"           => $duration < 8 ? 0 :($duration -8),
                "ordinary_time_humans" => $someTime->format($ordnary_time_humans_foormat),
                "extra_time_humans"    => $someTimeExtra ? $someTimeExtra->format($extra_time_humans_format) : 0,
            ];
            $this->_times = (object) $data;
        }
        return $this->_times;
    }

    public function getUrlFileAttribute(){
        return route('route.pdf', $this->id);
    }
}
