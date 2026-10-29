<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FoxconDocument extends Model
{
    use SoftDeletes;
    protected $fillable  = ['documento','numero','timestamp','nit','usuario','grabacion','anulado','sync','observation'];
    protected $appends = ['fecha','hora','fecha_grabacion','hora_grabacion'];
    protected $hidden  = ['created_at','updated_at','deleted_at', 'timestamp','grabacion'];


    public function details(){
        return $this->hasMany(FoxconDocumentDetail::class);
    }

    public function document(){
        return $this->belongsTo(ConfigurarionHeadquarterFoxcon::class,'documento','nomenclature');
    }

    public function getNumeroAttribute($value){
        if   ($value) return str_pad($value, 6, '0', STR_PAD_LEFT);
        else return null;
    }

    public function getUsuarioAttribute($value){
        if   ($value) return str_pad($value, 3, '0', STR_PAD_LEFT);
        else return null;
    }

    public function getFechaAttribute(){
        return \Carbon\Carbon::parse($this->timestamp)->format('Y-m-d');
    }

    public function getHoraAttribute(){
        return \Carbon\Carbon::parse($this->timestamp)->format('H:i');
    }

    public function getFechaGrabacionAttribute(){
        return \Carbon\Carbon::parse($this->timestamp)->format('Y-m-d');
    }

    public function getHoraGrabacionAttribute(){
        return \Carbon\Carbon::parse($this->grabacion)->format('H:i');
    }
}
