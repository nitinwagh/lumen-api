<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email'    => $faker->unique()->email,
        'password' => app('hash')->make('123456'),
    ];
});

$factory->define(App\Post::class, function (Faker\Generator $faker) {
    return [
        'user_id' => rand(1, 100),
        'title'    => $faker->title,
        'description' => $faker->text,
        'status' => 1
    ];
});


$factory->define(App\Comment::class, function (Faker\Generator $faker) {
    return [
        'user_id' => rand(1, 100),
        'post_id' => rand(1, 500),
        'comment' => $faker->text,
        'status' => 1
    ];
});