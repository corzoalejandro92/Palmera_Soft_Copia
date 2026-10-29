<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\GeneralScope;

class DetailService extends Model
{
    use GeneralScope, SoftDeletes;
    
    protected $guarded = ['id'];
    protected $appends = ['duration'];

    public function Service ()
   {
      return $this->belongsTo(Service::class);
   }

   public function headquarters(){
      return $this->belongsToMany(Headquarter::class);
   }
   
   public function getDurationAttribute(){
       switch ($this->categoryTime){
           case 1:
               return $this->length. ' Hora(s)'; 
               break;
           case 2:
               return $this->length. ' Minuto(s)'; 
               break;
           default:
               return 'Sin establecer'; 
               break;
       }
   }
}
