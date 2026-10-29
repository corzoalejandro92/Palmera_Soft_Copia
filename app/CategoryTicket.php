<?php

namespace App;

use Caffeinated\Shinobi\Models\Role;
use Illuminate\Database\Eloquent\Model;

class CategoryTicket extends Model
{
    protected $guarded = ['id'];

    public function ticket()
    {
        return $this->hasMany(TicketQuestion::class,'category_id');
    }
    public function roles()
    {
        return $this->belongsToMany(Role::class,'category_ticket_has_roles','category_ticket_id','rol_id');
    }
}
