<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\GeneralScope;

class Dose extends Model
{
    use SoftDeletes, GeneralScope;
    protected $guarded = ['id'];
    protected $with = ['unit'];
    protected $appends = ['text'];

    public function unit()
   {
      return $this->belongsTo(UnitDose::class,'unit_dose_id')
      ->withDefault(['name' => 'NO APLICA']);
   }

   function user(){
      return $this->belongsTo(User::class,'updated_user');
   }

   public function headquarter(){
      return $this->belongsTo(Headquarter::class,'headquarter_id')
              ->withDefault(['name' => 'Sin asignar'])
              ->with('zones');           
  }

  public function getTextAttribute(){
      $name = $this->start_range . (isset($this->end_range) ? '-'.$this->end_range : '') . ' ' .$this->unit->name;
      return $name;
  }
}
