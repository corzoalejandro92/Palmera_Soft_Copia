<?php

namespace App;

use App\General\DateTranslator;
use Illuminate\Database\Eloquent\Model;

class QuoteSerInfProd extends Model
{
    use DateTranslator;

    protected $table = 'quote_service_infestation_products';
    protected $guarded = ['id'];

    public function productable(){
        return $this->morphTo();
    }

    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function application(){
        return $this->belongsTo(Application::Class,'application_id');
    }

    public function area(){
        return $this->belongsTo(CrmArea::class,'crm_area_id');
    }

    public function control(){
        return $this->belongsTo(QuoteSerInf::class,'quote_service_infestation_id');
    }

}
