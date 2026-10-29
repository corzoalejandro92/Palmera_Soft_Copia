<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FoxconDocumentDetail extends Model
{
    use SoftDeletes;
    protected $fillable  = ['foxcon_document_id','documento','numero','timestamp','bodega','producto','cantidad','nit','tipoes','plot'];
    protected $appends = ['fecha','hora'];
    protected $hidden  = ['created_at','updated_at','deleted_at', 'timestamp'];


    public function product(){
        return $this->belongsTo(Product::class, 'producto','code');
    }

    public function warehouse(){
        return $this->belongsTo(Warehouse::class, 'bodega','code_foxcon');
    }
    public function getNumeroAttribute($value){
        if   ($value) return str_pad($value, 6, '0', STR_PAD_LEFT);
        else return null;
    }

    public function getProductoAttribute($value){
        if   ($value) return str_pad($value, 6, '0', STR_PAD_LEFT);
        else return null;
    }

    public function getFechaAttribute(){
        return \Carbon\Carbon::parse($this->timestamp)->format('Y-m-d');
    }

    public function getHoraAttribute(){
        return \Carbon\Carbon::parse($this->timestamp)->format('H:i');
    }
}
