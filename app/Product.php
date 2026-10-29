<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use App\Scopes\GeneralScope;


class Product extends Model
{
    use SoftDeletes, GeneralScope;
    protected $guarded = ['id'];
    protected $appends = ['full_avatar','draft'];
    protected $with    = ['unit'];


   public function units()
   {
      return $this->belongsTo (Unit::class,'unit_id')
      ->withDefault(['name' => 'Sin asignar']);
   }

   public function unit()
   {
      return $this->belongsTo (Unit::class,'unit_id')
      ->withDefault(['name' => 'Sin asignar']);
   }

   public function applications()
   {
      return $this->belongsTo(Application::class,'application_id');
   }

   public function application()
   {
      return $this->belongsTo(Application::class,'application_id');
   }

    public function categorys()
   {
       return $this->belongsTo (Category::class, 'category_id');
   }

   public function mechanisms()
   {
       return $this->belongsTo (Mechanism::class, 'mechanism_id');
   }

   public function fileExtend(){
      return $this->morphOne(FileExtend::class,'extendible');
   }

   public function servicios()
   {
      return $this->belongsToMany(Service::class);
   }
   
   public function services()
   {
      return $this->belongsToMany(Service::class);
   }

   public function infestations ()
   {
      return $this->belongsToMany(Infestation::class);
   } 

   public function detailProducts ()
   {
      return $this->hasMany(DetailProduct::class);
   }

   public function files ()
   {
      return $this->morphMany(File::class, 'filable');
   }

   public function file ()
   {
      return $this->morphMany(File::class, 'filable');
   }

   public function quoteSerInfProd(){
      return $this->morphOne(QuoteSerInfProd::class, 'productable');
   }

   public function quoteSerInfProds(){
      return $this->morphMany(QuoteSerInfProd::class, 'productable');
   }

   public function crmScheduleProduct(){
      return $this->morphOne(CrmScheduleControlProducts::class, 'productable');
   }

   public function crmScheduleProducts(){
      return $this->morphMany(CrmScheduleControlProducts::class, 'productable');
   }

   public function productWarehouses ()
    {
        return $this->belongsTo(Product::class,'id');
 
    }

    public function purchaseProductDetails ()
    {
        return $this->hasOne(Product::class);
 
    }

    public function detailPurchaseProduct ()
    {
       return $this->hasMany(PurchaseProductDetails::class,'product_id');
    }

    public function inventory(){
      return $this->hasMany(Inventory::class);
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
       return $this->hasMany(OrderPurchaseDetail::class);
    }

    public function OrderGeneralPurchasesDetail ()
    {
       return $this->hasMany(DetailGeneralOrderPurchase::class);
    }

   //  public function getBase64Attribute(){
   //     try {
   //       $base = $this->avatar ? base64_encode(file_get_contents(url($this->avatar))) : null;
   //     } catch (\Throwable $th) {
   //       $base  = null;
   //     }
        
   //      return $base;
   //  }

    public function methods(){
      return $this->hasMany(MethodProduct::class);
   }

   public function products(){
      return $this->belongsToMany(Product::class,'product_product','product_id','parent_product_id');
   }

   public function relatedProducts(){
      return $this->belongsToMany(Product::class,'product_product','product_id','parent_product_id');
   }

   public function parentProducts(){
      return $this->belongsToMany(Product::class, 'product_product','parent_product_id','product_id');
   }

}
