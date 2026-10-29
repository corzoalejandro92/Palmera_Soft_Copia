<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\GeneralScope;
use ErrorException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use GeneralScope, SoftDeletes;

    protected $guarded = ['id'];
    protected $fillable = ['path','name','type_id','observation'];
    protected $appends = ['url','extension','size','size_text'];
    protected $with  = ['fileType'];

    protected function columns(){
        return Schema::getColumnListing((new self())->getTable());
    }
    
    public function filable(){
        return $this->morphTo();
    }

    public function fileType(){
        return $this->belongsTo(FileType::class,'type_id');
    }

    public function getUrlAttribute(){
        return Storage::disk('public')->url($this->path);
    }
    public function getExtensionAttribute(){
       if(Storage::disk('public')->exists($this->path)) {
            $ext = pathinfo(storage_path($this->path), PATHINFO_EXTENSION);
        }else {
            $ext = 'unk';
        }

        return $ext;
        
    }

    public function getSizeAttribute(){
        if(Storage::disk('public')->exists($this->path)) {
            $size = Storage::disk('public')->size($this->path);
        }else {
            $size = 0;
        }
        return $size;
    }

    public function getSizeTextAttribute(){
        if(Storage::disk('public')->exists($this->path)) {
            $bytes = Storage::disk('public')->size($this->path);
        }else{
            $bytes = 0;
        }

        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 0) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 0) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 0) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    public function extends(){
        return $this->hasMany(FileExtend::class);
    }
    
}
