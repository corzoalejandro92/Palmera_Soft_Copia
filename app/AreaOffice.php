<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AreaOffice extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    public function services(){
        return $this->belongsToMany(QuoteService::class);
    }

    public function office ()
    {
       return $this->belongsTo(Office::class, 'office_id');
    }
    public function officeTrashed()
    {
       return $this->belongsTo(Office::class, 'office_id')->withTrashed();
    }

    public function file(){
        return $this->morphOne(File::class, 'filable');
    }

    public function files(){
        return $this->morphMany(File::class, 'filable');
    }

    public function apiFiles(){
        return $this->morphMany(\App\Models\Api\File::class, 'filable');
    }

    public function appFiles(){
        return $this->morphMany( \App\Models\Api\File::class, 'filable');
    }

    public function quoteSerInf(){
        return $this->belongsToMany(QuoteSerInf::class);
    }

    public function crmScheduleControls(){
        return $this->belongsToMany(CrmScheduleControl::class,'area_crm_sch_con');
    }

}
