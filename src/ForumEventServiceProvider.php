<?php
namespace Nicolasey\Forum;

use Illuminate\Events\EventServiceProvider;

class ForumEventServiceProvider extends EventServiceProvider
{
    protected $listen = [
        //Events\JwtLogin::class => [
        //    Listeners\LastLoginListener::class,
        //],
    ];
}