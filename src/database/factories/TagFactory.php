<?php

use App\Models\CRM\Tag\Tag;
use Faker\Generator as Faker;

$factory->define(Tag::class, function (Faker $faker) {
    $socialArr = [
        'facebook' => '#4466f2',
        'twitter' => '#38a4f8',
        'youtube' => '#f83838',
        'amazon' => '#dad910',
        'ebay' => '#da9810',
    ];

    return [
        'name' => $this->faker->unique()
            ->randomElement(
                [
                    'sales', 'promotion', 'branding', 'pre-sales',
                    'summer', 'winter', 'crishmass', 'trend',
                    'hot', 'testimonial', 'social', 'membership',
                    'campaign', 'investment', 'Relationship', 'Management',
                    'ROI', 'IoT', 'Networking', 'Data Mining',
                    'facebook', 'youtube', 'twitter', 'instagram',
                    'amazon', 'ebay', 'alibaba', 'rakuten',
                ]
            ),
        'color_code' => function (array $attributes) use ($socialArr) {
            return array_key_exists($attributes['name'], $socialArr)
            ? $socialArr[$attributes['name']]
            : random_color_code();
        },
    ];
});
