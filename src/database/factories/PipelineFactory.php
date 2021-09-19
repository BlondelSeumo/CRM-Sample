<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\Models\Core\Auth\User;
use App\Models\CRM\Pipeline\Pipeline;

$factory->define(Pipeline::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->randomElement(['Advertising', 'Promotion', 'Branding', 'Sales']),
        'created_by' => $faker->randomElement(User::query()->get('id')),
    ];
});
