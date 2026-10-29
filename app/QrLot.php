<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\GeneralScope;

class QrLot extends Model
{
    use GeneralScope;
    protected $with    = ['headquarter'];

    public function qrs(){
        return $this->hasMany(Qr::class);
    }

    public function actives(){
        return $this->hasMany(Qr::class)->whereNotNull('file_extend_id');
    }

    public function paperSize(){
        return $this->belongsTo(PaperSize::class,'paper_size_id');
    }

    public function headquarter(){
        return $this->belongsTo(Headquarter::class,'headquarter_id');
    }
}


