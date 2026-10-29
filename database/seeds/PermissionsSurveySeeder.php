<?php

use Caffeinated\Shinobi\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsSurveySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission = new Permission();
         $permission->name        = 'Ver respuestas encuestas';
         $permission->slug        = 'polls.survey.answer.index';
         $permission->description = 'Ver resultados de las encuestas';
         $permission->save();
         $permission->roles()->sync([6,12]);
    }
}
