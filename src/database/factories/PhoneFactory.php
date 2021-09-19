<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CRM\Phone\Phone;
use Faker\Generator as Faker;

$factory->define(Phone::class, function (Faker $faker) {
    return [
        'value' => $faker->randomElement(['017', '019', '018', '016']) . mt_rand(10000000, 99999999),
        'type_id' => $faker->numberBetween(1, 2),
        'contextable_type' => $faker->randomElement(['App\Models\CRM\Person\Person', 'App\Models\CRM\Organization\Organization']),
        'contextable_id' => $faker->numberBetween(1, 15)
    ];
});
