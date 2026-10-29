<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConfigurarionHeadquarterFoxcon extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];
    
    public function warehouse(){
        return $this->belongsTo(Warehouse::class,'warehouse_id');
    }

    public function typeDocument(){
        return $this->belongsTo(DocumentTypeFoxcon::class,'document_type_id');
    }

    public function type(){
        return $this->belongsTo(DocumentTypeFoxcon::class,'document_type_id');
    }
}