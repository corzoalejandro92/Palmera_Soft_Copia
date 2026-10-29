<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\GeneralScope;

class Diagnostic extends Model
{
    use SoftDeletes, GeneralScope;

    protected $guarded= ['id'];

    public function status(){
        return $this->belongsTo(DiagnosticStatus::class, 'status_id');
    }

    public function quote(){
        return $this->belongsTo(Quote::class, 'quote_id');
    }

    public function file(){
        return $this->morphOne(File::class, 'filable');
    }

    public function files(){
        return $this->morphMany(File::class, 'filable');
    }

    public function createdBy(){
        return $this->belongsTo(User::class, 'created_user_id');
    }
    public function updatedBy(){
        return $this->belongsTo(User::class, 'updated_user_id');
    }
    public function Office(){
        return $this->belongsTo(Office::class, 'office_id');
    }


}
