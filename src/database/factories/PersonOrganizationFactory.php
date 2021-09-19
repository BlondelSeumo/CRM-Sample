<?php

use Faker\Generator as Faker;
use App\Models\CRM\PersonOrganization;

$factory->define(PersonOrganization::class, function (Faker $faker) {
    return [
        'person_id' => $this->faker->numberBetween(1, 15),
        'organization_id' => $this->faker->numberBetween(1, 15),
        'job_title' => $this->faker->jobTitle,
    ];
});
