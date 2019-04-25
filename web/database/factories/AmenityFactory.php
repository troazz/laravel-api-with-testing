<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Amenity;
use Faker\Generator as Faker;

$factory->define(Amenity::class, function (Faker $faker) {
    return [
        'name'        => $faker->words(rand(1, 3), true),
        'description' => implode("\n", $faker->sentences(rand(1, 5))),
    ];
});
