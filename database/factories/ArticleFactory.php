<?php

namespace Database\Factories;

use App\Entities\Article;
use App\Entities\Blogger;
use DateTime;
use Faker\Generator;
use LaravelDoctrine\ORM\Testing\Factory;
use LogicException;

/** @var Factory $factory */
$factory->define(Article::class, function (Generator $faker, array $attributes) use ($factory) {
    if (! isset($attributes['category'])) {
        throw new LogicException('Provide article category trough attributes.');
    }

    return [
        'title' => $faker->title,
        'content' => $faker->realText,
        'publishedAt' => null,
        'created' => new DateTime(),
        'author' => $attributes['author'] ?? $factory->of(Blogger::class)->create(),
        'category' => $attributes['category'],
    ];
});

/** @var Factory $factory */
$factory->defineAs(Article::class, 'published', function (Generator $faker, array $attributes) use ($factory) {
    if (! isset($attributes['category'])) {
        throw new LogicException('Provide article category trough attributes.');
    }

    return [
        'title' => $faker->title,
        'content' => $faker->realText,
        'publishedAt' => new DateTime(),
        'created' => new DateTime(),
        'author' => $attributes['author'] ?? $factory->of(Blogger::class)->create(),
        'category' => $attributes['category'],
    ];
});
