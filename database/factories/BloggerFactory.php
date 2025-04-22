<?php

namespace Database\Factories;

use App\Entities\Blogger;
use App\Enums\BloggerRole;
use DateTime;
use Faker\Generator;
use LaravelDoctrine\ORM\Testing\Factory;

/** @var Factory $factory */
$factory->define(Blogger::class, function (Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'role' => BloggerRole::BLOGGER,
        'created' => new DateTime(),
    ];
});

$factory->defineAs(Blogger::class, 'admin', function (Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'role' => BloggerRole::ADMIN,
        'created' => new DateTime(),
    ];
});
