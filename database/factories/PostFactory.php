<?php

use Faker\Generator as Faker;

$factory->define(\Nicolasey\Forum\Models\Post::class, function (Faker $faker) {
    static $body;
    static $topic_id;
    static $author_id;

    return [
        'body' => ($body) ? $body : $faker->name,
        'topic_id' => ($topic_id) ? $topic_id : factory(\Nicolasey\Forum\Models\Topic::class)->create()->id,
        'author_type' => config("forum.author.classname"),
        'author_id' => ($author_id) ? $author_id : factory(config("forum.author.class"))->create()->id
    ];
});