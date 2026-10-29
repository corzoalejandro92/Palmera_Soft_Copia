<?php

use Caffeinated\Shinobi\Models\Permission;
use Illuminate\Database\Seeder;

class UnitDosesPermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    

        //permisos de unidades de dosis
        $permission = new Permission();
        $permission->name        = 'Listar unidad de dosis';
        $permission->slug        = 'unit-doses.index';
        $permission->description = 'Listar los registros de unidad de dosis';
        $permission->save();
        $permission->roles()->sync([1,2,5,7,8,10,6,12]);
        
        $permission = new Permission();
        $permission->name        = 'Detalles de Unidad de Dosis';
        $permission->slug        = 'unit-doses.show';
        $permission->description = 'Ver todos los detalles de unidad de dosis';
        $permission->save();
        $permission->roles()->sync([1,2,5,7,8,10,6,12]);
        
        $permission = new Permission();
        $permission->name        = 'Crear Unidad de Dosis';
        $permission->slug        = 'unit-doses.create';
        $permission->description = 'Crear registros de unidad de dosis';
        $permission->save();
        $permission->roles()->sync([1]);
        
        $permission = new Permission();
        $permission->name        = 'Editar Unidad de Dosis';
        $permission->slug        = 'unit-doses.edit';
        $permission->description = 'Editar registros de unidad de dosis';
        $permission->save();
        $permission->roles()->sync([1]);
        
        $permission = new Permission();
        $permission->name        = 'Eliminar Unidad de Dosis';
        $permission->slug        = 'unit-doses.destroy';
        $permission->description = 'Eliminar los registros de unidad de dosis';
        $permission->save();
        $permission->roles()->sync([1]);
    }
}
