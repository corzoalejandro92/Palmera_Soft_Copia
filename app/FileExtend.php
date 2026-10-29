<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class FileExtend extends Model
{

    use SoftDeletes;
    protected $guarded = ['id'];
    protected $casts = ['reported_products' => 'array', ];

    public function file(){
        return $this->belongsTo(\App\File::class, 'file_id');
    }

    public function fileTrashed(){
        return $this->belongsTo(\App\File::class, 'file_id')->withTrashed();
    }

    public function extendible(){
        return $this->morphTo();
    }

    public function qr(){
    	return $this->hasOne(Qr::class,'file_extend_id')->orderBy('id', 'desc');
    }

     public function userTracking(){
        return $this->morphOne(UserTracking::class, 'trackable');
    }
    public function record()
    {
        return $this->hasMany(TechnicalReport::class, 'file_extend_id')->orderBy('id', 'desc');
    }    
    
    public function lastTechnicalReport(){
        return $this->hasOne(TechnicalReport::class,'file_extend_id')->orderBy('created_at','DESC');
    }

    public function technicalReports(){
        return $this->hasMany(TechnicalReport::class,'file_extend_id')->orderBy('created_at','DESC');
    }



    public function reports(){
        return $this->hasMany(MechanismReport::class,'file_extend_id')->orderBy('created_at','DESC');
    }
    public function lastReports(){
        return $this->hasMany(MechanismReport::class,'file_extend_id')->orderBy('created_at','DESC')->limit(3);
    }

    public function inventories(){
        return $this->hasMany(Inventory::class,'file_extend_id')->with('product:id,name');
    }
}
