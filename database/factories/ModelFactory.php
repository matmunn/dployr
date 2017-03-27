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

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'group_id' => function () {
            return factory(App\Models\Group::class)->create()->id;
        }
    ];
});

$factory->define(App\Models\Group::class, function (Faker\Generator $faker) {
    return [
        'group_name' => 'Example Group',
        'plan_id' => function () {
            return factory(App\Models\Plan::class)->create()->id;
        }
    ];
});

$factory->define(App\Models\Plan::class, function (Faker\Generator $faker) {
    return [
        'name' => "Demo Plan",
        'price' => 500,
        'repository_limit' => 0,
        'user_limit' => 0,
    ];
});

$factory->define(App\Models\Repository::class, function (Faker\Generator $faker) {
    return [
        'name' => "Example Repo",
        'url' => "git@example.com:example/repo.git",
        'group_id' => function () {
            return factory(App\Models\Group::class)->create()->id;
        }
    ];
});

$factory->define(App\Models\Environment::class, function (Faker\Generator $faker) {
    return [
        'name' => "Default Environment",
        'branch' => "master",
        'repository_id' => function () {
            return factory(App\Models\Repository::class)->create()->id;
        },
        'current_commit' => "foobar",
        "deploy_mode" => App\Models\Environment::DEPLOY_MODE_AUTO
    ];
});
