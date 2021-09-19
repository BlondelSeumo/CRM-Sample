<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Core\Auth\User;
use App\Models\CRM\Deal\Deal;
use App\Models\CRM\Discussion\Discussion;
use Faker\Generator as Faker;

$factory->define(Discussion::class, function (Faker $faker) {
    return [
        'commentable_type' => $faker->randomElement(['App\Models\CRM\Deal\Deal']),
        'commentable_id' => $this->faker->randomElement(Deal::query()->get('id')),
        'comment_body' => $faker->realText(50),
        'commented_by' => $this->faker->randomElement(User::query()->get('id')),
        'comment_id_of_reply' => $this->faker->randomElement(User::query()->get('id'))
    ];
});
