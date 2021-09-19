<?php

use App\Models\Core\Status;
use App\Models\CRM\Deal\Deal;
use Faker\Generator as Faker;
use App\Models\CRM\Stage\Stage;
use App\Models\CRM\Deal\LostReason;
use App\Models\CRM\Pipeline\Pipeline;

$factory->define(Deal::class, function (Faker $faker) {
    $pipeline_id = $this->faker->randomElement(
        Pipeline::query()->pluck('id')->toArray()
    );

    $stage_id = $this->faker->randomElement(
        Stage::where('pipeline_id', $pipeline_id)
            ->pluck('id')
            ->toArray()
    );

    return [
        'title' => $this->faker->sentence($nbWords = 6, $variableNbWords = true),
        'value' => $this->faker->numberBetween(10000, 100000),
        'pipeline_id' => $pipeline_id,
        'stage_id' => $stage_id,
        'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s
         standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
         It has survived not only five centuries, but also the leap into electronic typesetting',
        'lost_reason_id' => $this->faker->randomElement(LostReason::query()->get('id')),
        'status_id' => $this->faker->randomElement(Status::whereType('deal')->get('id')),
        'created_by' => $faker->numberBetween(1, 3),
        'owner_id' => $faker->numberBetween(1, 3),
        'contextable_type' => $faker->randomElement(['App\Models\CRM\Person\Person', 'App\Models\CRM\Organization\Organization']),
        'contextable_id' => $faker->numberBetween(1, 15),
        'expired_at' => $this->faker->randomElement([null, $this->faker->date]),
        'created_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
    ];
});
