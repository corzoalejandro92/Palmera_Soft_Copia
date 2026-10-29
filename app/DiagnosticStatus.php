<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\GeneralScope;

class DiagnosticStatus extends Model
{
    use SoftDeletes, GeneralScope;
    
    protected $table    = 'diagnostic_status';
    protected $guarded  = ['id'];
}
