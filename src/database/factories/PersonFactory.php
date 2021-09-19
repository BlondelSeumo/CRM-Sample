<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Core\Auth\User;
use App\Models\CRM\Contact\ContactType;
use App\Models\CRM\Person\Person;
use Faker\Generator as Faker;

$factory->define(Person::class, function (Faker $faker) {
    return [
        'name' => $faker->firstName .' '. $faker->lastName,
        'address' => $faker->address,
        'contact_type_id' => $faker->randomElement(ContactType::query()->get('id')),
        'created_by' => $faker->randomElement(User::query()->get('id')),
        'owner_id' => $faker->randomElement(User::query()->get('id')),
        'country_id' => $faker->randomElement(App\Models\CRM\Country\Country::query()->get('id')),
        'area' => $faker->name,
        'state' => $faker->state,
        'city' => $faker->city,
        'zip_code' => $faker->randomNumber(4)
    ];
});
