<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Scopes\GeneralScope;
use Illuminate\Support\Arr;

class TicketQuestion extends Model
{
    use SoftDeletes, GeneralScope;

    protected $guarded = ['id'];

     // Column for soft deletes
    protected $dates = ['read_at','created_at','updated_at'];
    protected $appends = ['created_at_diff', 'read_at_diff'];
    
    public function office()
    {
        return $this->belongsTo(Office::class,'office_id');
    }
    public function priority()
    {
        return $this->belongsTo(PriorityTicket::class,'priority_id');
    }
    public function registered()
    {
        return $this->belongsTo(User::class,'registered_by_id');
    }
    public function category()
    {
        return $this->belongsTo(CategoryTicket::class,'category_id');
    }
    
    public function read()
    {
        return $this->belongsTo(User::class,'read_by_id');
    }
    public function answers()
    {
        return $this->hasMany(TicketAnswer::class,'question_id')->orderBy('id', 'asc');
    }
    public function scopeViewTicket($query)
    {
        $roles=Auth::user()->roles->pluck('slug');
        $rolesArray = Arr::flatten($roles);
        $clave =array_search('commercial', $rolesArray);
        if($clave !== false ){
            unset($rolesArray[$clave]);
        }
        if(!$roles->contains('root')){
            if($roles->contains('commercial')){
                $query->whereHas('office', function (Builder $office) {
                    $office->whereHas('crm', function (Builder $crm) {
                        $crm->where('user_id',Auth::user()->id);
                    });
                })->whereHas('category', function (Builder $category) {
                    $category->whereHas('roles', function (Builder $role) {
                        $role->where('slug','commercial');
                    });
                }); 
                $query->orwhereHas('category', function (Builder $category) use($rolesArray) {
                    $category->whereHas('roles', function (Builder $role) use($rolesArray) {
                        $role->whereIn('slug',$rolesArray);
                    });
                });
            }else{
                $query->whereHas('category', function (Builder $category) use($rolesArray) {
                    $category->whereHas('roles', function (Builder $role) use($rolesArray) {
                        $role->whereIn('slug',$rolesArray);
                    });
                });
            }
        }
        $query->headquarter();
    }

    public  function getCreatedAtDiffAttribute(){
        return $this->created_at->diffForHumans();
    }
    public function getReadAtDiffAttribute(){
        return $this->read_at != null ? $this->read_at->diffForHumans() : null;
    }
}
