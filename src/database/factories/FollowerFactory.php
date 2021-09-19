<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CRM\Follower\Follower;
use Faker\Generator as Faker;

$factory->define(Follower::class, function (Faker $faker) {
    return [
        'person_id' => $faker->randomElement(\App\Models\CRM\Person\Person::query()->get('id')),
        'contextable_type' => $faker->randomElement(['App\Models\CRM\Deal\Deal']),
        'contextable_id' => $faker->numberBetween(1, 50)
    ];
});
