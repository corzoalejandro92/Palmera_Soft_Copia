<?php

use Caffeinated\Shinobi\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionPeakPlateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $permission = new Permission();
        $permission->name        = 'Listar Pico y placa';
        $permission->slug        = 'peakPlate.index';
        $permission->description = 'listar Pico y placa';
        $permission->save();
        $permission->roles()->sync([8,10]);

        
        $permission = new Permission();
        $permission->name        = 'Crear Pico y placa';
        $permission->slug        = 'peakPlate.create';
        $permission->description = 'Crear Pico y placa';
        $permission->save();
        $permission->roles()->sync([8,10]);

        $permission = new Permission();
        $permission->name        = 'Editar Pico y placa';
        $permission->slug        = 'peakPlate.edit';
        $permission->description = 'Editar Pico y placa';
        $permission->save();
        $permission->roles()->sync([8,10]);

        $permission = new Permission();
        $permission->name        = 'Eliminar Pico y placa';
        $permission->slug        = 'peakPlate.destroy';
        $permission->description = 'Eliminar Pico y placa';
        $permission->save();
        $permission->roles()->sync([8,10]);


    }
}