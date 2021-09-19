<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Core\Auth\User;
use App\Models\Core\Status;
use App\Models\CRM\Deal\Deal;
use App\Models\CRM\Proposal\Proposal;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Builder;

$factory->define(Proposal::class, function (Faker $faker) {
    $open_deal_id = Deal::query()->whereHas('status', function (Builder $builder) {
        $builder->where('type', 'deal')
            ->where('name', 'status_open');
    })->inRandomOrder()->pluck('id')->first();
    return [
        'subject' => $faker->sentence($nbWords = 6, $variableNbWords = true),
        'content' => $faker->sentence($nbWords = 15, $variableNbWords = true),
        'status_id' => $faker->randomElement(Status::whereType('proposal')->get('id')),
        'deal_id' => $open_deal_id,
        'owner_id' => $faker->randomElement(User::query()->get('id')),
        'created_by' => $faker->randomElement(User::query()->get('id')),
        'expired_at' => $faker->randomElement([null, $faker->dateTime]),
        'sent_at' => $faker->randomElement([$faker->dateTime]),
        'accepted_at' => $faker->randomElement([$faker->dateTime])
    ];
});
