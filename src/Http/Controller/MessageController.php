<?php
namespace Nicolasey\Forum\Http\Controller;

use Nicolasey\Forum\Models\Post;
use Nicolasey\Forum\Models\Topic;

class MessageController
{
    /**
     * Get all messages from topic
     *
     * @param Topic $topic
     * @return mixed
     */
    public function index(Topic $topic)
    {
        return $topic->messages;
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

        try {
            $post = Post::create($data);
            return $post;
        } catch (\Exception $exception) {
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
        $post->load(['topic']);
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
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}