<?php
namespace Nicolasey\Forum\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kalnoy\Nestedset\NodeTrait;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Forum extends Model implements Sortable
{
    use NodeTrait, SoftDeletes, HasSlug, SortableTrait;

    protected $guarded = [];
    protected $table = "forum_forums";
    protected $with = ["children", "topics","lastPost"];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public $sortable = [
        'order_column_name' => 'order',
        'sort_when_creating' => true,
    ];

    /**
     * Forum's topics
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    /**
     * Last post for the scope is kept in the object to avoid useless things
     *
     * @returns \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lastPost()
    {
        $this->belongsTo(Post::class, "last_post");
    }
}