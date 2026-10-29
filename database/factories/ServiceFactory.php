<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Service;
use Faker\Generator as Faker;

$avatars = [
    "custom-mouse",
    "custom-infection",
    "custom-insect",
    "custom-wash",
    "custom-tent",
    "custom-fix",
    "custom-leak"
    
];

$factory->define(Service::class, function (Faker $faker) use($avatars) {
    return [
        "name" => $faker->sentence,
        "avatar" => $avatars[rand(0, count($avatars) - 1)],
        "description" =>$faker->sentence,
        
    ];
});
