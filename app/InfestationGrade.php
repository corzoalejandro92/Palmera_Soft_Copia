<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\GeneralScope;

class InfestationGrade extends Model
{
    use SoftDeletes, GeneralScope;
    protected $guarded = ['id'];
    protected $appends = ['grade'];


    public function crm(){
        return $this->belongsTo(Crm::class, 'crm_id')->withTrashed();
    }

    public function office(){
        return $this->belongsTo(Office::class,'office_id')->withTrashed();
    } 

    public function schedule(){
        return $this->belongsTo(CrmSchedule::class, 'crm_schedule_id');
    }
    

    public function control(){
        return $this->belongsTo(CrmScheduleControl::class, 'crm_schedule_control_id');
    }

    public function pest(){
        return $this->belongsTo(ControlInfestation::class, 'control_infestation_id')->withTrashed();
    }

    public function headquarter()
    {
       return $this->belongsTo(Headquarter::class,'headquarter_id');
    }

    public function user(){
        return $this->belongsToMany(User::class, 'office_user');
    }
    public function getGradeAttribute()
    {
        $text="Nulo";
        if($this->value == 1) $text = "Bajo";
        if($this->value == 2) $text = "Medio";
        if($this->value == 3) $text = "Alto";
        return $text;
    }
}
