<?php

namespace Database\Factories;

use App\Entities\ArticleCategory;
use Database\Faker\ArticleCategoryProvider;
use DateTime;
use Faker\Generator;
use LaravelDoctrine\ORM\Testing\Factory;

/** @var Factory $factory */
$factory->define(ArticleCategory::class, function (Generator $faker) {
    $faker->addProvider(new ArticleCategoryProvider($faker));

    return [
        'name' => $faker->unique()->articleCategory(),
        'created' => new DateTime(),
    ];
});
