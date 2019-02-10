<?php
return [
    "author" => [
        "class" => App\User::class,
        "classname" => "App\User",
    ],
    "middleware" => ['api.auth']
];