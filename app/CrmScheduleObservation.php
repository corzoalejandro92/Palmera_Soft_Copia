<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\GeneralScope;

class CrmScheduleObservation extends Model
{

    use SoftDeletes, GeneralScope;
    protected $guarded = ['id'];

    public function crmSchedule(){
        return $this->belongsTo(CrmSchedule::class, 'crm_schedule_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }
    
}
