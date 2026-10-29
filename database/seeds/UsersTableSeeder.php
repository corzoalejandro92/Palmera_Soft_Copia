<?php

use Illuminate\Database\Seeder;
use Caffeinated\Shinobi\Models\Role;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       
        factory(App\User::class,15)->create()->each(function($u) {
            $role = 0;
            while(in_array($role, [0,1,2,3])){
                $role = Role::all()->random()->id;
            }
            $u->roles()->sync([$role]);
            if($role == 11){
                $u->warehouse()->create([
                    "name" => $u->name,
                    "parent" => ($u->headquarter->id == 1 ? 2:3),
                    "buttons" => ['add' => false, 'buy'=> false, 'conf'=> true, 'delete' =>false, 'edit'=> false, 'transf' => true],
                    "icon" => 'technical',
                    "action" => true
                ]);
            }
        });


        //creación del usuario root super admin del sistema
        $user= User::create([
            'name'      => 'Super Administrador 1',
            'position'  => 'Gerente General',
            'email'     => 'superadmin1@palmerajunior.com',
            'password'  => bcrypt('sup3r4dmin1*'),
            'avatar'    => 'images/default/user.jpg',
            'remember_token' => str_random(10),
            'headquarter_id' => 1, 
        ]);
        $user->roles()->sync([1]);

        $user= User::create([
            'name'      => 'Super Administrador 2',
            'position'  => 'Ing. Coordinador MIP',
            'email'     => 'superadmin2@palmerajunior.com',
            'password'  =>  bcrypt('sup3r4dmin2*'),
            'avatar'    => 'images/default/user.jpg',
            'remember_token' => str_random(10),
            'headquarter_id' => 1, 
        ]);
        $user->roles()->sync([1]);
        

        $user= User::create([
            'name'  => 'Cristian Yepes B',
            'position'  => 'Técnico Especialista',
            'email'  => 'cyepes@swebsas.com',
            'document' => '1034008455',
            'phone'     => 3206073411,
            'password' => bcrypt('devlocal123'),
            'avatar' => '/storage/app/public/avatars/2020/04/5ea1dde52ab10.jpeg',
            'remember_token' => str_random(10),
            'headquarter_id' => 2,
        ]);
        $user->roles()->sync([11]);
        $user->availabilities()->sync([1,2,4,6]);
        $user->services()->sync([1,2,3]);
        $user->crms()->sync([1]);
        $user->zones()->sync([2]);
        $user->warehouse()->create([
            "name" => $user->name,
            "parent" => 3,
            "buttons" => ['add' => false, 'buy'=> false, 'conf'=> true, 'delete' =>false, 'edit'=> false, 'transf' => true],
            "icon" => 'technical',
            "action" => true
        ]);

        $user= User::create([
            'name'  => 'Alexander Narvaez P.',
            'position'  => 'Técnico Especialista',
            'email'  => 'anarvaez@swebsas.com',
            'document' => '1046708409',
            'phone'     => 3206073411,
            'password' => bcrypt('devlocal123'),
            'avatar' => '/storage/app/public/avatars/2020/04/5ea1de2f852e7.png',
            'remember_token' => str_random(10),
            'headquarter_id' => 2,
        ]);
        $user->roles()->sync([11]);
        $user->availabilities()->sync([1,2,3]);
        $user->services()->sync([1,2,4,5]);
        $user->crms()->sync([2]);
        $user->zones()->sync([3]);
        $user->warehouse()->create([
            "name" => $user->name,
            "parent" => 3,
            "buttons" => ['add' => false, 'buy'=> false, 'conf'=> true, 'delete' =>false, 'edit'=> false, 'transf' => true],
            "icon" => 'technical',
            "action" => true
        ]);


        $user= User::create([
            'name'  => 'Jeison Carmona F.',
            'position'  => 'Gerente Administrativo',
            'email'  => 'jcarmona@swebsas.com',
            'document' => '1036668399',
            'phone'     => 3206073411,
            'password' => bcrypt('devlocal123'),
            'avatar' => '/storage/app/public/avatars/2020/04/5ea1defea838b.jpeg',
            'remember_token' => str_random(10),
            'headquarter_id' => 2,
        ]);
        $user->roles()->sync([1,2]);

        $user= User::create([
            'name'  => 'Oscar Martinez Pinzon',
            'position'  => 'Coordinador MIP',
            'email'  => 'kflorez@swebsas.com',
            'password' => bcrypt('devlocal123'),
            'avatar' => '/storage/app/public/avatars/2020/04/5ea1e08610da2.jpeg',
            'remember_token' => str_random(10),
            'headquarter_id' => 1,
        ]);
        $user->roles()->sync([6]);


        $user= User::create([
            'name'  => 'Alejandra Vargas Angel',
            'position'  => 'Agente Comercial',
            'email'  => 'avargas@swebsas.com',
            'password' => bcrypt('devlocal123'),
            'avatar' => '/storage/app/public/avatars/2020/04/5ea1e26d898b4.png',
            'remember_token' => str_random(10),
            'headquarter_id' => 2,
        ]);
        $user->roles()->sync([4]);
        $user->crms()->sync([1]);

        $user= User::create([
            'name'  => 'Monica Castrillón D.',
            'email'  => 'mcastrillon@swebsas.com',
            'password' => bcrypt('devlocal123'),
            'avatar' => '/storage/app/public/avatars/2020/04/5ea1e8cf83782.jpeg',
            'remember_token' => str_random(10),
            'headquarter_id' => 2,
        ]);
        $user->roles()->sync([4]);
        $user->crms()->sync([2,3]);
  
        

    }
}
