<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TechnicalReport extends Model
{
    use SoftDeletes;

    protected $casts = ['reported_products' => 'array']; 
    protected $guarded = ['id'];

    public function pictures(){
        return $this->hasMany(TechnicalReportPicture::class, 'technical_report_id');
    }

    public function type(){
        return $this->belongsTo(TechnicalReportType::class, 'technical_report_type_id');
    }

    public function extend(){
        return $this->belongsTo(FileExtend::class, 'file_extend_id')->withTrashed();
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pest(){
        return $this->belongsTo(ControlInfestation::class, 'control_infestation_id');
    }

    public function crmSchedule(){
        return $this->belongsTo(CrmSchedule::class, 'crm_schedule_id');
    }

    public function file(){
        return $this->belongsTo(\App\File::class, 'file_id');
    }

    public function area(){
        return $this->belongsTo(\App\AreaOffice::class, 'area_office_id');
    }



}
