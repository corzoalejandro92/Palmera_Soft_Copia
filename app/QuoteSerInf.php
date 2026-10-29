<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\General\DateTranslator;
use Jenssegers\Date\Date;


class QuoteSerInf extends Model
{
    use DateTranslator;

    protected $table = 'quote_service_infestations';
    protected $guarded = ['id'];

    public function infestation(){
        return $this->belongsTo(Infestation::class, 'infestation_id')->withTrashed();
    }

    public function products(){
        return $this->hasMany(QuoteSerInfProd::class,'quote_service_infestation_id')->withTrashed();
    }

    public function areas(){
        return $this->belongsToMany(AreaOffice::class, 'area_customer_quote_service_infestation','quote_service_infestation_id')->withTrashed();
    }


    public function periodicity(){
        return $this->belongsTo(Periodicity::class,'periodicity_id')->withTrashed();
    }

    public function timeUnit(){
        return $this->belongsTo(TimeUnit::class, 'time_unit_id');
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

}
