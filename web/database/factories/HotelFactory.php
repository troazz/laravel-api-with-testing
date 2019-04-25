<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Hotel;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;

$factory->define(Hotel::class, function (Faker $faker) {
    return [
        'name'              => $faker->company,
        'description'       => implode("\n", $faker->sentences(rand(1, 5))),
        'address'           => $faker->address,
        'longitude'         => $faker->longitude,
        'latitude'          => $faker->latitude,
        'commission_type'   => Arr::random([Hotel::COMMISSION_PERCENTAGE, Hotel::COMMISSION_EXACT]),
        'commission_amount' => $faker->numberBetween(10, 100),
    ];
});
