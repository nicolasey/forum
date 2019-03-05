<?php
namespace Nicolasey\Forum\Http\Controller;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Controller;
use Nicolasey\Forum\Models\Post;
use Nicolasey\Forum\Models\Topic;
use DB;

class MessageController extends Controller
{
    /**
     * Get all messages from topic
     *
     * @param Topic $topic
     * @return mixed
     */
    public function index(Topic $topic)
    {
        $topic->load("posts");
        return $topic;
    }

    /**
     * Store a topic
     *
     * @param Topic $topic
     * @return mixed
     * @throws \Exception
     */
    public function store(Topic $topic)
    {

        $data = request()->only(['author_id', 'body']);
        $data['author_type'] = config("forum.author.classname");
        $data['topic_id'] = $topic->id;

        DB::beginTransaction();
        try {
            $post = Post::create($data);
            $this->evaluateLastPost($topic, $post);
            DB::commit();
            return $post;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    /**
     * Get a message
     *
     * @param Topic $topic
     * @param Post $post
     * @return Post
     */
    public function show(Topic $topic, Post $post)
    {
        $post->load(['topic', 'topic.posts']);
        return $post;
    }

    /**
     * Update a post
     *
     * @param Topic $topic
     * @param Post $post
     * @return Post
     * @throws \Exception
     */
    public function update(Topic $topic, Post $post)
    {
        $data = request()->only(['author_id', 'body', 'topic_id']);
        $data['author_type'] = config("forum.author.classname");

        try {
            $post->update($data);
            return $post;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Delete a post
     *
     * @param Topic $topic
     * @param Post $post
     * @throws \Exception
     */
    public function destroy(Topic $topic, Post $post)
    {
        try {
            $post->delete();
            if($topic->posts()->count() <= 0) $topic->delete();
            $this->evaluateLastPost($topic, $post);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    private function evaluateLastPost(Topic $topic, Post $post)
    {
        // Concerned post is/was not lastPost of topic, then do nothing
        if($topic->lastPost->id != $post->id) return;

        // Concerned post is/was not lastPost of forum, then do nothing
        dd($topic->forum->getAncestors());
    }

    /**
     * Set last post as given post for the model
     *
     * @param Model $model
     * @param Post $post
     * @throws \Exception
     */
    private function setLastPost(Model $model, Post $post)
    {
        try {
            $model->update(['last_post' => $post->id]);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}