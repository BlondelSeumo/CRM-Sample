<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CRM\Email\Email;
use Faker\Generator as Faker;

$factory->define(Email::class, function (Faker $faker) {
    return [
        'value' => $faker->safeEmail,
        'type_id' => $faker->numberBetween(1, 2),
        'contextable_type' => $faker->randomElement([
            'App\Models\CRM\Person\Person',
            // 'App\Models\CRM\Organization\Organization'
        ]),
        'contextable_id' => $faker->numberBetween(1, 15)
    ];
});
