<?php
namespace App\General;

use \App\Headquarter;
use App\Warehouse;
use Illuminate\Http\Request;


  trait Warehouses
{
    public static function setWarehouse($model, $headquarterId = null,$type='customer', $foxconId = null){
        
        //definir los botones habilitados/deshabilitados en la bodega que se va crear
        //definir la bodega padre de la bodega que se va a crear
        //definir el nombre de la bodega a crear

        switch($type){
            case 'customer':
                $buttons    = ['add' => false, 'inventory' =>true, 'buy'=> false, 'conf'=> true, 'delete' =>false, 'edit'=> false, 'transf' => true];
                $parent     =  self::getParentWarehouse($headquarterId);
                $customerName = $model->crm()->get()->last()->name;
                $name       = "$customerName $model->name ($model->address, {$model->city->name})";
            break;
            case 'technical':
                $buttons    = ['add' => false, 'inventory' =>true, 'buy'=> false, 'conf'=> true, 'delete' =>false, 'edit'=> false, 'transf' => true];
                $parent     =  self::getParentWarehouse($headquarterId);
                $name       = "$model->name";
                break;
            default:
                return false;
            break;
        }

        /*
         *Buscar si ya existe una bodega para el registro 
         * del modelo padre o sino crearlo
         */
        
        if($model->warehouse()->exists()){
            $warehouse = $model->warehouse()->update([
                 "name"         => $name,
                 "code_foxcon"  => $foxconId
            ]);
        }else{
            $warehouse  = $model->warehouse()->create([
                "name" => $name,
                "parent" => $parent,
                "buttons" => $buttons,
                "code_foxcon"  => $foxconId,
                "icon" => $type,
                "action" => true
            ]);
        } 
        return $warehouse;
    }
    
    private static function getParentWarehouse($headquarterId = null){

        //en caso de que no se mande un id de sede, tomar la de la sesión actual
        if(is_null($headquarterId)){
            $headquarterId = session('headquarter_id');
        }

        //consultar la sede manada en la variable $headquarterId
        $headquarter = Headquarter::with('warehouse')->find($headquarterId);
        if($headquarter && $headquarter->warehouse){
            return $headquarter->warehouse->id;
        }else{
            return null;
        }
        
    }

    public static function updateWarehouseName($model, $string){
        $warehouse = $model->warehouse()->update(["name" => $string]);
    }
}