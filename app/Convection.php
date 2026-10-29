<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\GeneralScope;
use Illuminate\Support\Facades\Storage;

class Convection extends Model
{
    use SoftDeletes, GeneralScope;
    protected $guarded = ['id'];
    protected $appends = ['full_avatar',];
    protected $with = ['category','products'];
    public function products(){
        return $this->belongsToMany(Product::class)->withPivot('quantity')->withTrashed();
    }

    public function user(){
        return $this->belongsTo(User::class,'updated_user')->withTrashed();
    }

    public function category(){
        return $this->belongsTo(CategoryConvention::class,'category_conventions_id')->withTrashed();
    }

    public function files ()
    {
       return $this->morphMany(File::class, 'filable')->withTrashed();
    }
 
    public function file ()
    {
       return $this->morphMany(File::class, 'filable')->withTrashed();
    }
 

    public function getFullAvatarAttribute()
    {
       return  $this->avatar ? url($this->avatar) : asset('images/default/convection.png');
    }

    public function fileExtend(){
        return $this->morphOne(FileExtend::class,'extendible');
     }


}
