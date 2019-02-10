<?php

use Faker\Generator as Faker;

$factory->define(\Nicolasey\Forum\Models\Forum::class, function (Faker $faker) {
    static $name;
    static $content;
    static $parent_id;

    return [
        'name' => ($name) ? $name : $faker->name,
        'content' => ($content) ? $content : $faker->sentence,
        'parent_id' => ($parent_id) ? $parent_id : null,
    ];
});