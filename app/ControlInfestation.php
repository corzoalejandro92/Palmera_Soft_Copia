<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\GeneralScope;
use Illuminate\Support\Facades\Storage;

class ControlInfestation extends Model
{
    use SoftDeletes, GeneralScope;
    protected $guarded = ["id"];
    protected $appends = ["full_avatar", "slug"];

    public function infestation(){
        return $this->belongsTo(Infestation::class,'infestation_id')->withTrashed();
    }

    public function files ()
    {
       return $this->morphMany(File::class, 'filable');
    }
 
    public function file ()
    {
       return $this->morphMany(File::class, 'filable');
    }
 
    public function fileExtend(){
        return $this->morphOne(FileExtend::class,'extendible');
     }

    public function getFullAvatarAttribute(){
        return $this->avatar != null ? Storage::disk('public')->url($this->avatar) : asset('images/default/infestation.png');
    }

    public function getSlugAttribute(){

        $v = '';
        if(preg_match_all('/\b(\w)/',strtoupper($this->name),$m)) {
            $v = implode('',$m[1]); // $v is now SOQTU
        }
        return  $v;
    }
}
