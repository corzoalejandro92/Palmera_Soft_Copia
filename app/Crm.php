<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\GeneralScope;

use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditingAuditable;

class Crm extends Model implements Auditable
{
   use SoftDeletes, GeneralScope, AuditingAuditable;
   
    protected $guarded = ['id','crm_id','headquarters'];
    protected $appends = ['full_name','full_avatar'];

    public function crmCategory ()
   {
      return $this->belongsTo (CrmType::class,'crm_type_id');
   }

   public function meanContact ()
   {
      return $this->belongsTo (MeanContact::class,'mean_contact_id');
   }

   public function offices ()
   {
      return $this->hasMany(Office::class);
   }

   public function cdocument(){
      return $this->hasMany(DocumentCustomer::class);
   }
  

   public function quotes()
   {
      return $this->hasMany(Quote::class);
   }

   public function users(){
        return $this->belongsToMany(User::class, 'crm_user');
   }

   public function user(){
      return $this->belongsTo(User::class, 'user_id');
   }

   public function contracts(){
        return $this->hasMany(Contract::class);
   }

   public function getAvatarAttribute($value){
        return $value ?? asset('images/default/customer.png');
   }

   public function crmTracking(){
      return $this->morphOne(CrmTracking::class, 'trackable');
   }

   public function file(){
      return $this->morphOne(File::class, 'filable');
   }

  public function files(){
      return $this->morphMany(File::class, 'filable');
  }


  public function schedules(){
      return $this->hasMany(CrmSchedule::class);
  }

  public function externalNotes(){
   return $this->hasMany(ExternalNotes::class);
  }

  public function services()
   {
      return $this->belongsToMany(Service::class)->withPivot('id','technical_quantity');
   }

   public function headquarters(){
      return $this->belongsToMany(Headquarter::class);
   }

   public function getFullNameAttribute(){
      return $this->name . (isset($this->commercial_name) ? ' / '.$this->commercial_name : '');
   }

   public function getFullAvatarAttribute(){
      return  $this->avatar ? url($this->avatar) : asset('images/default/customer.jpg');
   }

  
}
