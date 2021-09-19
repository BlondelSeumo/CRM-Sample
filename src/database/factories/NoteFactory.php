<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Core\Auth\User;
use App\Models\Core\Status;
use App\Models\CRM\Note\Note;
use Faker\Generator as Faker;

$factory->define(Note::class, function (Faker $faker) {
    return [
        'note' => $faker->text(200),
        'status_id' => $faker->randomElement(Status::whereName('status_done')->get('id')),
        'created_by' => $faker->randomElement(User::query()->get('id')),
        'noteable_type' => 'App\Models\CRM' . $faker->randomElement(['\Deal\Deal', '\Person\Person', '\Organization\Organization']),
        'noteable_id' => $faker->numberBetween(1, 15)
    ];
});
