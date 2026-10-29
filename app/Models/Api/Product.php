<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use App\Scopes\GeneralScope;


class Product extends Model
{
    use SoftDeletes, GeneralScope;
    protected $guarded = [];
    protected $appends = ['base64'];


   public function units()
   {
      return $this->belongsTo (\App\Unit::class,'unit_id')
      ->withDefault(['name' => 'Sin asignar']);
   }

   public function unit()
   {
      return $this->belongsTo (\App\Unit::class,'unit_id')
      ->withDefault(['name' => 'Sin asignar']);
   }

   public function applications()
   {
      return $this->belongsTo(\App\Application::class,'application_id');
   }

    public function categorys()
   {
       return $this->belongsTo (\App\Category::class, 'category_id');
   }

   public function mechanisms()
   {
       return $this->belongsTo (\App\Mechanism::class, 'mechanism_id');
   }

   public function fileExtend(){
      return $this->morphOne(\App\FileExtend::class,'extendible');
   }

   public function servicios()
   {
      return $this->belongsToMany(\App\Service::class);
   }
   
   public function services()
   {
      return $this->belongsToMany(\App\Service::class);
   }

   public function infestations ()
   {
      return $this->belongsToMany(Infestation::class);
   } 

   public function detailProducts ()
   {
      return $this->hasMany(\App\DetailProduct::class);
   }

   public function files ()
   {
      return $this->morphMany(File::class, 'filable');
   }

   public function file ()
   {
      return $this->morphMany(File::class, 'filable');
   }


   public function productWarehouses ()
    {
        return $this->belongsTo(\App\Product::class,'id');
 
    }

    public function purchaseProductDetails ()
    {
        return $this->hasOne(\App\Product::class);
 
    }

    public function detailPurchaseProduct ()
    {
       return $this->hasMany(\App\PurchaseProductDetails::class,'product_id');
    }

    public function getFullAvatarAttribute()
    {
       return  $this->avatar ? url($this->avatar) : asset('images/default/product.png');
    }


    public function getDraftAttribute()
    {
      return is_null($this->unit_id) ? true : false;
    }

    public function OrderPurchasesDetail ()
    {
       return $this->hasMany(\App\OrderPurchaseDetail::class);
    }

    public function OrderGeneralPurchasesDetail ()
    {
       return $this->hasMany(\App\DetailGeneralOrderPurchase::class);
    }

    public function getBase64Attribute(){
        $base = base64_encode(file_get_contents(url($this->avatar)));
        return $base;
    }
}
