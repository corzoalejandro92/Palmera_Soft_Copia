<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\GeneralScope;
use Illuminate\Support\Facades\Storage;

class Infestation extends Model
{
    use SoftDeletes, GeneralScope;

    protected $guarded = ['id'];
    protected $appends = ['full_avatar','base64'];

    public function product()
    {
        return $this->belongsToMany (Product::class);
    }

    public function services()
    {
        return $this->belongsToMany (Service::class);
    }

    public function fileExtend(){
        return $this->morphOne(\App\Models\Api\FileExtend::class,'extendible');
     }


    public function getFullAvatarAttribute(){

        return $this->avatar != null ? Storage::disk('public')->url($this->avatar) : asset('images/default/infestation.png');
    }

    public function getBase64Attribute(){
        // try {
        //     $arrContextOptions=array(
        //         "ssl"=>array(
        //             "verify_peer"=>false,
        //             "verify_peer_name"=>false,
        //         ),
        //     );  
        //     $base = base64_encode(file_get_contents(url($this->avatar ?? 'images/default/infestation.png'),false, stream_context_create($arrContextOptions)));
        // } catch (\Exception $th) {
        //     $base = null;
        // }
        
        // return $base;

        return $this->avatar != null ? Storage::disk('public')->url($this->avatar) : asset('images/default/infestation.png');
    }

      public function apiFiles ()
   {
      return $this->morphMany(\App\Models\Api\File::class, 'filable');
   }

   public function plagas(){
       return $this->hasMany(ControlInfestation::class);
   }
}
