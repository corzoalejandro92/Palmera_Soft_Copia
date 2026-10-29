<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\ApiScope;

class Workschedule extends Model
{
    use SoftDeletes,ApiScope;

    protected $guarded  = ["id"];
    protected $casts    = ['json' => 'object','services_data' => 'object'];
    protected $appends  =  ["text","url_to_edit"];

    public function services(){
        return $this->hasMany(CrmSchedule::class);
    }

    public function crm(){
        return $this->belongsTo(Crm::class, 'crm_id');
    }

    public function quote(){
        return $this->belongsTo(Quote::class, 'quote_id');
    }

    public function office(){
        return $this->belongsTo(Office::class,'office_id');
    } 


    public function headquarter(){
        return $this->belongsTo(Headquarter::class,'headquarter_id');
    }


    public function user(){
        return $this->belongsTo(User::class,'user_id')->withTrashed();
    } 

    public function crmTracking(){
        return $this->morphOne(CrmTracking::class, 'trackable');
    }

    public function getTextAttribute(){
        $name = $this->name;
        if(!is_null($this->quote_id)) $name .= " de la cotización #". $this->quote->consecutive;
        if(!is_null($this->user_id)) $name .= " por ". $this->user->name;
        if(!is_null($this->created_at)) $name .= " del ". $this->created_at->format('d/m/Y');

        return $name;
    }

    public function getUrlToEditAttribute(){
        $quote = $this->quote;
        if($quote){
            return route('crm.edit-workschedule', ['crm' => $quote->crm_id, 'workschedule' => $this->id,'office' => $quote->office_id]) ."#tabSchedule";
        }else{
            return route('crm.edit-workschedule', ['crm' => $this->crm_id, 'workschedule' => $this->id,'office' => $this->office_id]) ."#tabSchedule";
        }
        
    }
}
