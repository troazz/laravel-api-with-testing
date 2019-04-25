<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Currency;
use App\Models\Hotel;
use App\Models\Room;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Room::class, function (Faker $faker) {
    return [
        'name'          => $faker->company,
        'description'   => implode("\n", $faker->sentences(rand(1, 5))),
        'access_code'   => Str::random(7),
        'max_occupancy' => $faker->numberBetween(1, 10),
        'net_rate'      => $faker->numberBetween(100, 1000),
        'currency_id'   => function () {
            return Currency::inRandomOrder()->first();
        },
        'hotel_id' => function () {
            return Hotel::inRandomOrder()->first();
        },
    ];
});
