<?php
namespace Nicolasey\Forum\Models;

use Cmgmyr\Messenger\Models\Message;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Message
{
    use SoftDeletes;

    protected $table = "forum_posts";

    /**
     * The relationships that should be touched on save.
     *
     * @var array
     */
    protected $touches = ['topic'];
}