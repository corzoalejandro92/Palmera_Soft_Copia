<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\GeneralScope;

class Infestation extends Model
{
    use SoftDeletes, GeneralScope;

    protected $guarded = ['id'];
    protected $appends = ['full_avatar','base64'];

    public function product()
    {
        return $this->belongsToMany (Product::class);
    }

    public function fileExtend(){
        return $this->morphOne(\App\FileExtend::class,'extendible');
     }


    public function getFullAvatarAttribute(){

        return url($this->avatar);
    }

    public function getBase64Attribute(){
        $base = base64_encode(file_get_contents(url($this->avatar)));
        return $base;
    }
    
    public function files ()
   {
      return $this->morphMany(File::class, 'filable');
   }
}
