<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Menu
 *
 * @author json
 */
namespace App\General;

class Menu {
    public static function isActAction($controllerParam, $actionParam){
        
        $stringRoute= \Route::getCurrentRoute()->getActionName();
        $explode = explode('@', $stringRoute);
        if(isset($explode[1])){
            $action= $explode[1];
            $explodeController = explode('\\', $explode[0]);
            $controller= str_replace('Controller','', end($explodeController));

            if( strtolower($controller) === strtolower($controllerParam) && strtolower($action) === strtolower($actionParam)){
                return 'active';
            }
        }
        
        
        
        return '';
    }
    
    public static function isController($controllerParam){
        
        $stringRoute= \Route::getCurrentRoute()->getActionName();
        $explode = explode('@', $stringRoute);
        if(isset($explode[1])){
        $action= $explode[1];
        $explodeController = explode('\\', $explode[0]);
        $controller= str_replace('Controller','', end($explodeController));
        
            if( strtolower($controller) === strtolower($controllerParam)){
                return 'open';
            }
      }

        return '';
    }
    
    public static function getControllerName(){
        $route = \Route::getCurrentRoute()->getActionName();
        $explode = explode('@', $route);
        $explodeArray=  explode('\\', $explode[0]);
        return end($explodeArray);
        
    }
    public static function getActionName(){
        $route = \Route::getCurrentRoute()->getActionName();
        $explode = explode('@', $route);
        $explodeArray=  explode('\\', $explode[1]);
        return end($explodeArray);
        
    }

    public static function randomColor(){
            $str = "#";
            for($i = 0 ; $i < 6 ; $i++){
                $randNum = rand(0, 15);
                    switch ($randNum) {
                        case 10: $randNum = "A"; 
                        break;
                        case 11: $randNum = "B"; 
                        break;
                        case 12: $randNum = "C"; 
                        break;
                        case 13: $randNum = "D"; 
                        break;
                        case 14: $randNum = "E"; 
                        break;
                        case 15: $randNum = "F"; 
                        break; 
                    }
                $str .= $randNum;
                }
            return $str;
       }
       //Fin de la funcion.
}
