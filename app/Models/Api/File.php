<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Scopes\GeneralScope;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use SoftDeletes, GeneralScope;

    protected $table = 'files';
    protected $guarded = ['id'];
    protected $fillable = ['path','name','type_id','observation'];
    protected $appends = ['base64'];
    protected $with  = ['fileType'];

    protected function columns(){
        return Schema::getColumnListing((new self())->getTable());
    }
    
    public function filable(){
        return $this->morphTo();
    }

    public function fileType(){
        return $this->belongsTo(\App\FileType::class,'type_id');
    }

    public function extends(){
        return $this->hasMany(\App\FileExtend::class);
    }

    public function getBase64Attribute(){

        try {
            $arrContextOptions=array(
                "ssl"=>array(
                    "verify_peer"=>false,
                    "verify_peer_name"=>false,
                ),
            );
            $base = base64_encode(file_get_contents( storage_path('app/public').'/'.$this->path,false, stream_context_create($arrContextOptions)));
        return $base;
        } catch (\Exception $ex) {
            return null;
        }
        
    }
    
}
