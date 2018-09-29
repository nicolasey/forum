<?php
namespace Nicolasey\Auth\Traits;

use Nicolasey\Forum\Models\Post;

trait PostsInForum
{
    /**
     * User's posts
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function posts()
    {
        return $this->morphMany(Post::class, "author");
    }
}