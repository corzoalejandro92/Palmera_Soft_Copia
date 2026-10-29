<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\GeneralScope;

class FileType extends Model
{

	use GeneralScope;


    protected $guarded = ['id'];
    protected $append  = ['translate_slug'];

    public function file(){
        return $this->hasMany(File::class);
    }

    public function getTranslateSlugAttribute(){
        switch ($this->slug){
            case 'user':
                return 'Módulo Usuarios'; 
                break;
            case 'product':
                return 'Módulo Productos'; 
                break;
            case 'vehicle':
                return 'Módulo Vehículos'; 
                break;
            case 'crm':
                return 'Módulo CRM'; 
                break;
            case 'plan':
                return 'Módulo Planos'; 
                break;
            default:
                return 'Otro'; 
                break;
        }
    }
}
