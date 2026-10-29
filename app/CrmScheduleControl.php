<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jenssegers\Date\Date;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditingAuditable;

class CrmScheduleControl extends Model implements Auditable
{
    use softDeletes, AuditingAuditable;
    protected $guarded = ['id'];
    protected $table   = 'crm_schedule_controls';
    protected $appends = ['datec','duration'];

    public function infestation(){
        return $this->belongsTo(Infestation::class, 'infestation_id')->withTrashed();
    }

    public function infestationGrade(){
        return $this->hasMany(InfestationGrade::class, 'crm_schedule_control_id');
    }
    public function infestationGrad(){
        return $this->hasOne(InfestationGrade::class, 'crm_schedule_control_id');
    }

    public function consumptions(){
        return $this->hasMany(Consumption::class, 'control_id');
    }

    public function reportedProducts(){
        return $this->hasMany(ReportedProduct::class, 'control_id');
    }

    public function product(){
        return $this->belongsTo(Product::class,'product_id')->withTrashed();
    }

    public function area(){
        return $this->belongsTo(CrmArea::class,'crm_area_id')->withTrashed();
    }

    public function application(){
        return $this->belongsTo(Application::class,'application_id')->withTrashed();
    }
    public function dose(){
        return $this->belongsTo(Dose::class, 'dose_id')->withDefault([
            'text' => 'NO APLICA',
        ])->withTrashed();
    }

    public function service(){
        return $this->belongsTo(CrmSchedule::class,'crm_schedule_id')->withTrashed();
    }

     public function status()
    {
        return $this->belongsTo(CrmScheduleStatus::class,'status_id')->withTrashed();
    }

    public function expenses(){
        return $this->hasMany(InventoryExpense::class,'control_id')->orderBy('created_at','asc');
    }

    public function timeUnit(){
        return $this->belongsTo(TimeUnit::class, 'time_unit_id');
    }

    public function getDatecAttribute(){
        return new Date($this->date);
    }

    public function crmTracking(){
        return $this->morphOne(CrmTracking::class, 'trackable');
     }

     public function getDurationAttribute(){
        if($this->timeUnit){
            return $this->time_value * $this->timeUnit->seconds;
        }else{
            return 0;
        }
        
    }

    public function inventoryExpense(){
        return $this->hasMany(InventoryExpense::class, 'control_id');
    }
    


    
}
