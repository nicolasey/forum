<?php
namespace Nicolasey\Forum\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kalnoy\Nestedset\NodeTrait;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Forum extends Model
{
    use NodeTrait, SoftDeletes, HasSlug;

    protected $guarded = [];
    protected $table = "forum_forums";
    protected $with = ['lastPost'];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    /**
     * Last post for the scope is kept in the object to avoid useless things
     */
    public function lastPost()
    {
        $this->belongsTo(Post::class, "last_post");
    }
}