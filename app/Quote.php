<?php

namespace App;

use App\General\DateTranslator;
use App\Scopes\GeneralScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jenssegers\Date\Date;

use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditingAuditable;
class Quote extends Model implements Auditable
{
    use SoftDeletes, GeneralScope, DateTranslator,AuditingAuditable;

    protected $guarded = ["id"];
    protected $appends = ["code","approved_amount", 'approved_services'];

    public function crms ()
    {
       return $this->belongsTo (Crm::class,'crm_id')->withTrashed();
    }

    public function offices ()
   {
      return $this->hasMany(Office::class)->withTrashed();
   }

   public function services(){
        return $this->hasMany(QuoteService::class);
   }

   public function crm(){
        return $this->belongsTo(Crm::class, 'crm_id')->withTrashed();
   }

    public function coordinator(){
        return $this->belongsTo(User::class, 'coordinator_id')->withTrashed();
    }
    public function commercial(){
        return $this->belongsTo(User::class, 'commercial_id')->withTrashed();
    }

    public function status(){
        return $this->belongsTo(QuoteStatus::class,'status_id');
    }

    public function office(){
        return $this->belongsTo(Office::class,'office_id')->withTrashed();
    }

    public function contact(){
        return $this->belongsTo(Contact::class,'contact_id')->withTrashed();
    }

      public function contract(){
        return $this->hasOne(Contract::class);
    }

    public function crmTracking(){
        return $this->morphOne(CrmTracking::class, 'trackable');
    }

    public function headquarter(){
        return $this->belongsTo(Headquarter::class,'headquarter_id');
    }

    public function file(){
      return $this->morphOne(File::class, 'filable');
    }

    public function files(){
        return $this->morphMany(File::class, 'filable');
    }

    public function format(){
        return $this->belongsTo(QuoteTemplate::class, 'format_id');
    }

    public function hasDiagnostic(){
        return $this->hasOne(Diagnostic::class);
    }

    public function getPossibleDateAttribute($possible_date){
        return new Date($possible_date);
    }
    
    public function getExpireDateAttribute($expire_date){
        return new Date($expire_date);
    }

    public function getDateApprovedAtAttribute(){
        return new Date($this->approved_at);
    }

    public function getCodeAttribute(){

        if($this->commercial){
            if(preg_match_all('/\b(\w)/',mb_strtoupper($this->commercial->name),$m)) {
                $u = implode('',$m[1]); // $v is now SOQTU
            }
        }else{
            $u="";
        }
        

        if(!isset($this->headquarter->iata)){
            dd($this->commercial);
            if(preg_match_all('/\b(\w)/',mb_strtoupper($this->headquarter->name),$sm)) {
                $c = implode('',$sm[1]); // $v is now SOQTU
            }
        }else{
            $c = $this->headquarter->iata;
        }
        

        return  strtoupper("$c-".mb_substr($u, 0, 3)."-{$this->consecutive}");
    }

    public function getApprovedAmountAttribute(){
        if(in_array($this->status_id, [2,4,5,6])){
            $services =  $this->services()->where('approved',1)->get();
            return $services->sum('price');
        }else{
            return 0;
        }
    }

    public function getApprovedServicesAttribute(){
        if(in_array($this->status_id, [2,4,5,6])){
            $services =  $this->services()->where('approved',1)->get();
            return $services->count();
        }else{
            return 0;
        }
    }

}
