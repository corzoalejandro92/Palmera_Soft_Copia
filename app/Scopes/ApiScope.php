<?php

namespace App\Scopes;

use Illuminate\Support\Facades\Auth;
use App\General\Menu;
use Carbon\Carbon;

trait ApiScope{
    
    public function scopeGetRoute($query,$user,$date)
    {

        $date = Carbon::parse($date);
        return $query
            ->with([
                //'schedulings.office.areas.apiFiles.extends.extendible',
                //'schedulings.office.areas.apiFiles.extends.qr',
                //'schedulings.users.warehouse.inventory.detailInventories',
                'schedulings.users',
                'schedulings.contact',
                'schedulings.crm',
                'schedulings.crmSchedule.observations' => function($obs){ $obs->where('private',0);},
                'schedulings.crmSchedule.service.detailService',
                'schedulings.crmSchedule.controls.infestation',
                'schedulings.crmSchedule.controls.infestationGrade.pest.infestation',
                'schedulings.crmSchedule.controls.area',
                'schedulings.crmSchedule.controls.expenses.inventoryDetail',
                'schedulings.crmSchedule.controls.expenses.warehouse',
                'schedulings.crmSchedule.controls.product.categorys',
                'schedulings.crmSchedule.controls.dose',
                'schedulings.crmSchedule.controls.application',
                
            ])
            /*->whereHas('users', function($users) use($user){
                $users->where('user_id',$user);
            })*/
            ->where('user_id', $user)
            ->whereBetween('date',[$date->startOfDay()->format('Y-m-d H:i:s'),$date->endOfDay()->format('Y-m-d H:i:s')]);
    }

    public function scopeCheckDay($query,$user,$type)
    {
        return $query
                ->where('user_id',$user)
                ->where('user_tracking_type',$type)
                ->whereBetween('date',[date('Y-m-d 00:00:00'),date('Y-m-d 23:59:59')]);

    }
}
