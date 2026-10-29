<?php

namespace App;

use App\Scopes\GeneralScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jenssegers\Date\Date;


class QuoteService extends Model
{
    use SoftDeletes, GeneralScope;

    protected $guarded = ['id'];
    protected $appends = ['crm','office','contact','duration'];

    

    public function service(){
        return $this->belongsTo(Service::class, 'service_id')->withTrashed();
    }

    public function detailService(){
        return $this->belongsTo(DetailService::class, 'coverage_id')->withTrashed();
    }

    public function infestations(){
        //return $this->belongsToMany(infestations::class, 'quote_service_insfestation');
        return $this->hasMany(QuoteSerInf::class);
    }

    public function periodicity(){
        return $this->belongsTo(Periodicity::class,'periodicity_id')->withTrashed();
    }


    public function getPossibleDateAttribute($start_date){
        return new Date($start_date);
    }

    public function getStartDateAttribute($start_date){
        return new Date($start_date);
    }

    public function getEndDateAttribute($end_date){
        return new Date($end_date);
    }

    public function quote(){
        return $this->belongsTo(Quote::class, 'quote_id');
    }

    public function parameters(){
        return $this->hasMany(ParameterQuoteService::class);
        //return $this->belongsToMany(ParameterService::class, 'parameter_quote_service');
    }


    public function getCrmAttribute(){
        return $this->quote->crm;
    }

    public function getOfficeAttribute(){
        return $this->quote->office;
    }

    public function getContactAttribute(){
        return $this->quote->contact;
    }


    public function getDurationAttribute(){
        if($this->timeUnit){
            return $this->time_value * $this->timeUnit->seconds;
        }else{
            return 0;
        }
        
    }

}
