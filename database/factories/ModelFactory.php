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
        'email' => $faker->email,
    ];
});

$factory->define(App\Member::class, function (Faker\Generator $faker) {
    return [
        'firstname' => $faker->firstname,
        'insertion' => '',
        'surname' => $faker->lastname,
        'group' => $faker->randomElement([
            'Committee',
            'General Crew',
            'Kookcie',
            'Group 1',
            'Group 2',
            'Group 3',
            'Group 4',
            'Group 5',
            'Group 6',
            'Group 7',
            'Group 8',
        ]),
    ];
});
