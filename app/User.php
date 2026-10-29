<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Caffeinated\Shinobi\Concerns\HasRolesAndPermissions;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Notifications\CustomResetPasswordNotification;
use App\Scopes\GeneralScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditingAuditable;

class User extends Authenticatable implements Auditable 
{
    use Notifiable, HasRolesAndPermissions, SoftDeletes, GeneralScope, AuditingAuditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    /* protected $with = ['roles', 'crms', 'zones', 'headquarter']; // optimización para los appends */
    protected $appends = ['full_avatar','full_roles','full_headquarter', 'full_zones','short_name','medium_name','full_customers','signature_url'];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function sendPasswordResetNotification($token)
    {
       $this->notify(new CustomResetPasswordNotification($token));
    }

    public function sales(){
        return $this->hasMany(CrmExternalNote::class,'commercial_id');
    }

    public function areas(){
        return $this->hasMany(CrmArea::class);
    }
    
    public function headquarter(){
        return $this->belongsTo(Headquarter::class,'headquarter_id')
                ->withDefault(['name' => 'Sin asignar'])
                ->with('zones');           
    }
    
    public function roles(){
        return $this->belongsToMany(\Caffeinated\Shinobi\Models\Role::class,'role_user')
                ->withTimestamps();
    }
    
    public function documents(){
        return $this->hasMany(DocumentUser::class);
    }
    public function visits(){
        return $this->hasMany(CrmTracking::class, 'user_id');
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class,'commercial_id');
    }

    public function file(){
        return $this->morphOne(File::class, 'filable');
    }

    public function files(){
        return $this->morphMany(File::class, 'filable');
    }

    public function crms(){
        return $this->belongsToMany(Crm::class,'crm_user')->distinct();
    }

    public function offices(){
        return $this->belongsToMany(Office::class,'office_user');
    }

    public function services(){
        return $this->belongsToMany(Service::class,'service_user');
    }
    
    public function warehouse(){
        return $this->morphOne(Warehouse::class, 'warehousable');
    }

    public function vehicless(){
        return $this->hasMany(Vehicle::class);
    }
    
    public function availabilities(){
        return $this->belongsToMany(Availability::class);
    }

    public function zones(){
        return $this->belongsToMany(Zone::class,'user_zone');
    }

    public function schedulings(){
        return $this->belongsToMany(Scheduling::class,'scheduling_user')->orderBy('start','asc');
    }

    public function routes(){
        return $this->hasMany(Route::class);
    }

    public function coords(){
        return $this->hasMany(UserCoord::class);
    }

    public function getFullRolesAttribute(){
        return  $this->roles->pluck('name')->implode(' ,');
    }

    public function getFullCustomersAttribute(){
        return  $this->crms->pluck('name')->implode(', ');
    }

    public function getFullAvatarAttribute(){
        return  $this->signature ? Storage::url($this->avatar) : asset('images/default/user.jpg');
    }

    public function getSignatureUrlAttribute(){
        return  $this->signature ? Storage::url($this->signature) : asset('images/default/signature.png');
    }

    public function getFullZonesAttribute(){
        return  $this->zones->pluck('name')->implode(' ,','name');
    }

    public function getFullHeadquarterAttribute(){
        return  $this->headquarter->name;
    }

    public function getShortNameAttribute(){
        return explode(' ',$this->name)[0];
    }

    public function getMediumNameAttribute(){
        $name = explode(' ',$this->name);
        return  $name[0] . (isset($name[1]) ? ' '.$name[1]: '');
    }

    public static function withoutAppends(): Builder
    {
        $model = (new static);
        $model->setAppends([]);

        return $model->newQuery();
    }

}
