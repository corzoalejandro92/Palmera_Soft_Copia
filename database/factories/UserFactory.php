<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Headquarter;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(User::class, function (Faker $faker) {
    return [
        "name"      => $faker->name,
        "position"  => $faker->jobTitle,
        "email"     => $faker->unique()->safeEmail,
        "document"  => Str::limit($faker->unique()->bankAccountNumber, 10),
        "phone"     => Str::limit($faker->unique()->phoneNumber, 10),
        "password"  => bcrypt('123'),
        "remember_token" => Str::random(10),
        "avatar" => 'images/default/user.jpg',
        "headquarter_id" => Headquarter::all()->random()->id  
    ];
});
