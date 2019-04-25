<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Currency;
use Faker\Generator as Faker;

$factory->define(Currency::class, function (Faker $faker) {
    $symbol_location = collect(['suffix', 'prefix']);
    $symbol = collect(['$', 'Rp', '£', '¥', 'RM']);

    return [
        'name'            => $faker->country,
        'symbol_location' => $symbol_location->random(),
        'symbol'          => $symbol->random(),
        'code'            => $faker->currencyCode,
    ];
});
