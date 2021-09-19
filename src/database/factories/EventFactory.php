<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CRM\Event\Event;
use Faker\Generator as Faker;

$factory->define(Event::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'status_id' => $faker->randomElement(\App\Models\Core\Status::query()->get('id')),
        'created_by' => $faker->randomElement(\App\Models\Core\Auth\User::query()->get('id')),
        'contact_type_id' => $faker->randomElement(\App\Models\CRM\Contact\ContactType::query()->get('id')),
        'contextable_type' => 'App\Models\CRM' . $faker->randomElement(['\Deal\Deal', '\Person\Person', '\Organization\Organization']),
        'contextable_id' => $faker->numberBetween(1, 15)
    ];
});
