<?php

use Faker\Generator as Faker;

$factory->define(\Nicolasey\Forum\Models\Topic::class, function (Faker $faker) {
    static $subject;
    static $forum_id;

    return [
        'subject' => ($subject) ? $subject : $faker->name,
        'forum_id' => ($forum_id) ? $forum_id : factory(\Nicolasey\Forum\Models\Forum::class)->create()->id,
    ];
});