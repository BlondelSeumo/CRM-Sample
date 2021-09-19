<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Core\Status;
use Faker\Generator as Faker;
use App\Models\Core\Auth\User;
use App\Models\CRM\Activity\Activity;
use App\Models\CRM\Activity\ActivityType;

$factory->define(Activity::class, function (Faker $faker) {
    $index = $faker->randomElement(ActivityType::query()->get('id'));
    $name = ActivityType::where('id', $index->id)->get('name')->implode('name', ' ');
    $start_date = $faker->dateTimeBetween('-1 month', '+1 month', config('app.timezone'));
    $end_date = $start_date;
    $start_time = $faker->time('H:i:s');
    $end_time = \Carbon\Carbon::parse($start_time)->addMinutes(15);

    return [
        'title' => $faker->randomElement([
            $faker->name.' make a '.(string) $name.' with '.$faker->name,
            $faker->name.' sent an '.(string) $name.' to '.$faker->name.' at '.$faker->safeEmail,
            $faker->name.' scheduled a '.(string) $name.' with '.$faker->company.' at '.$faker->time('H:i'),
        ]),
        'activity_type_id' => $index,
        'contextable_type' => $faker->randomElement(['App\Models\CRM\Deal\Deal', 'App\Models\CRM\Person\Person', 'App\Models\CRM\Organization\Organization']),
        'contextable_id' => $faker->numberBetween(1, 15),
        'created_by' => $faker->randomElement(User::query()->get('id')),
        'status_id' => $faker->randomElement(Status::whereType('activity')->get('id')),
        'start_time' => $start_time,
        'end_time' => $end_time,
        'started_at' => $start_date,
        'ended_at' => $end_date,
    ];
});
