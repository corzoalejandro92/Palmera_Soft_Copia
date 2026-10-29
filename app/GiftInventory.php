<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GiftInventory extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];


    public function warehouse(){
        return $this->belongsTo(Warehouse::class,'warehouse_id');
    }
    
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }

    public function foxconDocument(){
        return $this->belongsTo(FoxconDocument::class,'document_foxcon_id');
    }

    public function typeDocumentFoxcon(){
        return $this->belongsTo(ConfigurarionHeadquarterFoxcon::class,'configuracion_document_foxcon_id');
    }
}
