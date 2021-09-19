<?php

use Faker\Generator as Faker;
use App\Models\CRM\Tag\Taggable;

$factory->define(Taggable::class, function (Faker $faker) {
    $taggable_type = ['App\Models\CRM\Deal\Deal' => true];

    return [
        'tag_id' => $this->faker->numberBetween(1, 28),
        'taggable_type' => 'App\Models\CRM'.$this->faker->randomElement(['\Deal\Deal', '\Person\Person', '\Organization\Organization']),
        'taggable_id' => function (array $attributes) use ($taggable_type) {
            return array_key_exists($attributes['taggable_type'], $taggable_type)
            ? $this->faker->numberBetween(1, 50)
            : $this->faker->numberBetween(1, 15);
        },
    ];
});
