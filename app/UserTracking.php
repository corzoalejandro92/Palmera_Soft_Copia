<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\ApiScope;
use Jenssegers\Date\Date;

class UserTracking extends Model
{
    use SoftDeletes,ApiScope;

    protected $guarded = ['id'];

    protected $appends = ['datedate'];

    public function trackable()
    {
        return $this->morphTo();
    }

    public function getDatedateAttribute(){
        return \Carbon\Carbon::parse($this->date)->format('Y-m-d');
    }
    public function getDateTrackingAttribute(){
        return new Date($this->date);
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id')->withTrashed();
    }
    public static function timeText($time,$sign=''){
        if($time < 0){ $time=abs($time); $sign = '-';}
        $hour = floor($time/60) ? floor($time/60) .' horas' : '';
        $minute = $time%60 ? $time%60 .' minutos' : '';
        return $hour && $minute ? $sign.$hour.' y '.$minute : $sign.$hour.$minute;
    }
}
