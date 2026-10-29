<?php

namespace App\Scopes;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\General\Menu;

trait GeneralScope{
    
    protected $guardedControllers = ['UserController','InventoryController'];
    
    /**
     * Scope para filtrar por la sede del usuario en cuestion
     */
    public function scopeHeadquarter($query,$strict = false, $column = 'headquarter_id'){

        if(
            Auth::user()->roles->pluck('slug')->contains('root') &&
            in_array(Menu::getControllerName(),$this->guardedControllers) &&
            !$strict   
          ){
            return $query;
        }else{
            return $query->where($column,session('headquarter_id'));
        }
        
    }
    
    public function scopeHeadquarterOffice($query,$column = 'headquarter_id'){

        if(
            Auth::user()->roles->pluck('slug')->contains('customer') 
          ){
            return $query;
        }else{
            return $query->whereHas('offices',function($squery) use($column){
                $squery->where($column,session('headquarter_id'));
            })->orWhereHas('headquarters', function($headquarter){
                $headquarter->where('headquarter_id', session('headquarter_id'));
            });//->orWhere('customer_national',true);
        }
        
    }

    public function scopeOneHeadquarterOffice($query,$column = 'headquarter_id'){

        return $query->whereHas('office',function($squery) use($column){
            $squery->where($column,session('headquarter_id'));
        });
    }

    public function scopeHeadquarterPivot($query,$column = 'headquarter_id'){

        return $query->whereHas('headquarters',function($squery) use($column){
            $squery->where($column,session('headquarter_id'));
        });
    }
    
    /**
     * Scope para filtrar por registros activos
     */
    public function scopeEnabled($query,$column = 'enabled'){
        return $query->where($column,true);
    }
    
    /**
     * Scope para filtrar por productos x servicios relacionados
     */
    public function scopeRelatedService($query,$service, $column = 'service_id'){
        return $query->whereHas('services',function($q) use ($service){
            $q->where('service_id',$service);
        });
    }

    /**
     * Scope para filtrar cotizaciones por el rol del usuario correspondiente
     */
    public function scopeQuoteUser($query){

        if(Auth::user()->roles->pluck('slug')->contains('mip_coordinator')){
            return $query->where(function($sq){
                $sq->whereIn('status_id',[2,4,5,6]);
            })->orWhere('commercial_id', Auth::user()->id);
        }else if(Auth::user()->roles->pluck('slug')->contains('commercial')){
            return $query->where('commercial_id',Auth::user()->id);  
        }else if(Auth::user()->roles->pluck('slug')->contains('customer')){
            return $query->where('crm_id',Auth::user()->id);  
        }

        return $this->scopeHeadquarter($query);
    }

    /**
     * Scope para filtrar una ruta de un técnico
     */
    public function scopeRouteUser($query,$user, $date){
        return $query->where('user_id',$user)
            ->whereBetween('date',
                [
                    Carbon::parse($date)->format('Y-m-d 00:00:00'),
                    Carbon::parse($date)->format('Y-m-d 23:59:00')
                ]);
    }

    /**
     * Scope para obtener una ruta desde un evento
     */
    public function scopeRouteScheduling($query,$schedule){
        return $query->whereHas('schedulings',function($q) use($schedule){
            $q->where('scheduling_id',$schedule);
        });

    }

    /**
     * scope para obtener solo los clientes asignados (asesores comerciales)
    */
    public function scopeCommercialCrm($query){
        if(
            Auth::user()->roles->pluck('slug')->contains('root') ||
            Auth::user()->roles->pluck('slug')->contains('admin') ||
            Auth::user()->roles->pluck('slug')->contains('boss_commercial') ||
            Auth::user()->roles->pluck('slug')->contains('mip_coordinator')
        ){
            return $query;
        }else{
            //si el rol es comercial
            return $query->where(function($q){
                $q->where('user_id', Auth::user()->id);
            })->orWhere(function($sq){
                
                $sq->WhereHas('users',function ($q){
                    $q->where('user_id', Auth::user()->id);
                });

            })->orWhere(function($osq){
                $osq->where('customer_national',true);
            });
        }

    }

    public function scopeInverseBetween($query,$column = 'date', $startDate, $endDate){
        $query->where($column,'>=',Carbon::parse($startDate)->format('Y-m-d 00:00:00'))
                ->where($column,'<=',Carbon::parse($endDate)->format('Y-m-d 23:59:59'));
    }

    public function scopeExclude($query, $value = array()) 
    {
      return $query->select( array_diff( $this->columns(), $value) );
    }


    /**
     * Scope para filtrar ordenes de compra por el rol del usuario correspondiente
     */
    public function scopeOrderPurchase($query,$strict = false, $column = 'headquarter_id'){

        if(
            (Auth::user()->roles->pluck('slug')->contains('boss_purchasing') &&
            in_array(Menu::getControllerName(),$this->guardedControllers) &&
            !$strict) || (Auth::user()->roles->pluck('slug')->contains('root') &&
            in_array(Menu::getControllerName(),$this->guardedControllers) &&
            !$strict) ||
            (Auth::user()->roles->pluck('slug')->contains('admin') &&
            in_array(Menu::getControllerName(),$this->guardedControllers) &&
            !$strict) 
          ){ 
            return $query;
        }else{
            return $query->where($column,session('headquarter_id'));
        }
        
    }
}