<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Volontaires::class, function (Faker $faker) {
    return [
        //
        'prenom' => $faker->firstName(),
        'nom' => $faker->lastName(),
        'ville' => $faker->city(),
        'phone' => $faker->phoneNumber(),
        'email' => $faker->email()
    ];
});
