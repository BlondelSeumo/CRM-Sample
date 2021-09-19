<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CRM\Deal\Deal;
use App\Models\CRM\Participant\Participant;
use App\Models\CRM\Person\Person;
use Faker\Generator as Faker;

$factory->define(Participant::class, function (Faker $faker) {
    return [
        'deal_id' => $faker->randomElement(Deal::query()->get('id')),
        'person_id' => $faker->randomElement(Person::query()->get('id'))
    ];
});
