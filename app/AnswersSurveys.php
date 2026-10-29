<?php

namespace App;

use App\Scopes\GeneralScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnswersSurveys extends Model
{
    use SoftDeletes, GeneralScope;

    protected $guarded = ['id'];

    public function detailsAnswer(){
        return $this->hasMany(AnswersSurveysDetail::class, 'answers_surveys_id');
    }
    public function office(){
        return $this->belongsTo(Office::class, 'office_id')->withTrashed();
    }
    public function crmSchedule(){
        return $this->belongsTo(CrmSchedule::class, 'crm_schedule_id')->withTrashed();
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }
    public function poll(){
        return $this->belongsTo(Poll::class, 'polls_id')->withTrashed();
    }

}
