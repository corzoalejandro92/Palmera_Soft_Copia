<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MechanismReport extends Model
{

    protected $appends = ['file_url'];

    protected $guarded = ['id'];
    public function type(){
        return $this->belongsTo(TechnicalReportType::class, 'technical_report_type_id');
    }

    public function extend(){
        return $this->belongsTo(FileExtend::class, 'file_extend_id')->withTrashed();
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function getFileUrlAttribute(){
        return $this->file_path ? Storage::url($this->file_path) : null;
    }

    public function scheduling(){
        return $this->belongsTo(Scheduling::class, 'scheduling_id');
    }
}
