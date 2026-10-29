<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\GeneralScope;
use Illuminate\Database\Eloquent\SoftDeletes;

class Qr extends Model
{
    protected $guarded  = ['id'];
    protected $casts = ['file_extend_id' => 'string'];
    use GeneralScope, SoftDeletes;

    public function extend(){
    	return $this->belongsTo(FileExtend::class,'file_extend_id')->where('extendible_type','App\Convection')->withTrashed();
    }

    public function qrlot(){
    	return $this-> belongsTo(QrLot::class, 'qr_lot_id');
    }

    public function schedule(){
        return $this->belongsTo(CrmSchedule::class, 'crm_schedule_id')->withTrashed();
    }

    public function scanBy(){
        return $this->belongsTo(User::class, 'scan_by')->withTrashed();
    }

    public function crm(){
        return $this->belongsTo(Crm::class, 'crm_id')->withTrashed();
    }

    public function office(){
        return $this->belongsTo(Office::class, 'office_id');
    }

    public function area(){
        return $this->belongsTo(CrmArea::class, 'crm_area_id');
    }
}
