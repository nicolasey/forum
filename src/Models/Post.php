<?php
namespace Nicolasey\Forum\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $table = "forum_posts";

    /**
     * The relationships that should be touched on save.
     *
     * @var array
     */
    protected $touches = ['topic'];

    /**
     * Get the author
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function author()
    {
        return $this->morphTo("author");
    }

    /**
     * Post's topic
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
}