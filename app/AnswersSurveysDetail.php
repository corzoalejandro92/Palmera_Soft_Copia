<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnswersSurveysDetail extends Model
{
    use SoftDeletes;
    
    protected $guarded = ['id'];
    
    public function answerSurveys(){
        return $this->belongsTo(AnswersSurveys::class,'answers_surveys');
    }

    public function question(){
        return $this->belongsTo(Question::class,'question_id')->withTrashed();
    }

    public function answer(){
        return $this->belongsTo(Answer::class,'answer_id')->withTrashed();
    }

}
