<?php

namespace Database\Factories;

use App\Entities\Blogger;
use App\Enums\BloggerRole;
use DateTime;
use Faker\Generator;
use Illuminate\Support\Facades\Hash;
use LaravelDoctrine\ORM\Testing\Factory;

/** @var Factory $factory */
$factory->define(Blogger::class, function (Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'role' => BloggerRole::BLOGGER,
        'password' => Hash::make('password'),
        'created' => new DateTime(),
    ];
});

$factory->defineAs(Blogger::class, 'admin', function (Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'role' => BloggerRole::ADMIN,
        'password' => Hash::make('password'),
        'created' => new DateTime(),
    ];
});
