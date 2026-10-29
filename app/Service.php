<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\GeneralScope;

class Service extends Model
{
    use SoftDeletes, GeneralScope;

    protected $guarded = ['id'];
    protected $appends = ['full_headquarter'];


    public function detailService()
    {
       return $this->hasMany(DetailService::class);
    }

    public function detailServices()
    {
        return $this->hasMany(DetailService::class);
    }

    public function parameters()
    {
        return $this->hasMany(ParameterService::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function infestations()
    {
        return $this->belongsToMany(Infestation::class);
    }

    public function headquarter(){
        return $this->belongsTo(Headquarter::class,'headquarter_id')
                ->withDefault(['name' => 'Sin asignar'])
                ->with('zones');           
    }

    public function offices(){
        return $this->belongsToMany(Office::class)->withTimestamps()->withPivot(['technical_quantity'])->using(OfficeService::class);
    }

    public function getFullHeadquarterAttribute(){
        return  $this->headquarter->name;
    }

}
