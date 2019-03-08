<?php

Broadcast::channel('forum.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});