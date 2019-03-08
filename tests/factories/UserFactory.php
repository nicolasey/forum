<?php

use Faker\Generator as Faker;

$factory->define(\Nicolasey\Forum\Tests\Models\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name
    ];
});