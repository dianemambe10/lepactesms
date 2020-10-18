<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\sms::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(1),
        'content' => $faker->sentence(15)
    ];
});
