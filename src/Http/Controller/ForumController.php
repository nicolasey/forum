<?php
namespace Nicolasey\Forum\Http\Controller;

use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Nicolasey\Forum\Models\Forum;
use Nicolasey\Forum\Models\Post;

class ForumController extends Controller
{
    /**
     * List core forums
     *
     * @return mixed
     */
    public function index()
    {
        $forums = Forum::where('parent_id', null)->get();
        $forums = $forums->load("children");
        return $forums;
    }

    /**
     * Show a forum
     *
     * @param Forum $forum
     * @return Forum
     */
    public function show(Forum $forum)
    {
        $forum->load([
            'children',
            'topics'
        ]);
        return $forum;
    }

    /**
     * Store a forum
     *
     * @throws \Exception
     * @return mixed
     */
    public function store()
    {
        try {
            $data = request()->only(['name', "content"]);

            $parent = (request()->only("parent_id")) ? request()->only("parent_id")['parent_id'] : null;
            $parent = Forum::find($parent);

            $forum = Forum::create($data);

            if($parent) {
                $forum->parent_id = $parent->id;
                $forum->save();
            }

            return $forum;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Update a forum
     *
     * @param Forum $forum
     * @return mixed
     * @throws \Exception
     */
    public function update(Forum $forum)
    {
        $data = request()->only(['name', "content"]);

        $parent = (request()->only("parent_id")) ? request()->only("parent_id")['parent_id'] : null;
        $newParent = Forum::find($parent);

        // Check if forum has to move
        $hasMoved = ($newParent && ($newParent->id !== $forum->parent_id));

        try {
            if($hasMoved && $forum->last_post) {
                // Build new breadcrumbs for given
                $newAncestors = $newParent->getAncestors();
                $newAncestors->prepend($newParent);

                $this->setAncestorsLastPost($newAncestors, $forum->lastPost);
            }

            $forum->update($data);

            if($hasMoved && $forum->last_post) {
                $ancestors = $forum->getAncestors();
                $this->setAncestorsLastPost($ancestors, $forum->lastPost);
            }

            return $forum;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Delete a forum
     *
     * @param Forum $forum
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Forum $forum)
    {
        try {
            $ancestors = $forum->getAncestors();
            $first = $ancestors->first();
            $lastPostIsAncestorLastPost = ($forum->last_post === $first->last_post);
            dd($forum->last_post);

            if($lastPostIsAncestorLastPost) {
                $lastPost = $this->evaluateNewLastPost($first);
                $this->setAncestorsLastPost($ancestors, $lastPost);
            }

            $forum->delete();
            return response()->json();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    private function evaluateNewLastPost(Forum $forum)
    {
        // topics
        $topics = $forum->topics->pluck('id')->toArray();
        $lastPost = Post::whereIn('topic_id', $topics)->last();

        // children
        $children = Forum::where('parent_id', $forum->id)->get();
        $children->load('lastPost');

        // collect all lastPosts
        $collection = new Collection();
        foreach ($children as $child) $collection->merge($child->lastPost);
        $collection->merge($lastPost);

        // evaluate
        $lastPost = $collection->sortBy('id')->last();

        // return Post
        return $lastPost;
    }

    /**
     * Set last post for ancestors
     *
     * @param Collection $ancestors
     * @param Post $post
     */
    private function setAncestorsLastPost(Collection $ancestors, Post $post)
    {
        foreach ($ancestors as $ancestor) {
            // if ancestor last post is newer, then we stop
            $ancestorLastPostIsNewer = ($ancestor->lastPost->id > $post->id);
            if($ancestorLastPostIsNewer) break;

            // Otherwise save this lastPost as last for the ancestor, and continue
            $ancestor->last_post = $post->id;
        }
    }
}