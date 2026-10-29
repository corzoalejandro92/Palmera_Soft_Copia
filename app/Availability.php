<?php

namespace App;

use App\Scopes\GeneralScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditingAuditable;
class Availability extends Model implements Auditable
{
    use SoftDeletes,GeneralScope,AuditingAuditable;

    protected $guarded = ["id"];
    protected $notFoundMessage = 'No se encontraron las disponibilades especificadas';
    protected $appends = ["full_name"];
    
    public function businessDays(){
        return $this->belongsToMany(BusinessDay::class);
    }
    
    public function workingHours(){
        return $this->hasMany(WorkingHour::class);
    }
    
    public function users(){
        return $this->belongsToMany(User::class);
    }

    public function getFullNameAttribute(){
        $fullname = $this->name;
       $wh =  $this->workingHours()->get();
       $fullname.= ' (';
       foreach($wh as $cwhe => $whr){
            $fullname.= $whr->start.'-'.$whr->end. (($cwhe+1) == $wh->count() ? '': ', ');
       }
       $fullname.= ')';

       return $fullname;
    }
}
