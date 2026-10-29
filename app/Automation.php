<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\General\DateTranslator;
use Jenssegers\Date\Date;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\GeneralScope;

use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditingAuditable;
class Automation extends Model implements Auditable
{
    use DateTranslator, GeneralScope, SoftDeletes, AuditingAuditable;

    protected $guarded = ['id'];
    protected $casts = [
         'date_range' => 'array',
         'availabilities' => 'array',
         'users' => 'array',
         'services' => 'array',
         'services_config' => 'array'
        ];

    public function routes(){
        return $this->hasMany(Route::class);
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }
}
