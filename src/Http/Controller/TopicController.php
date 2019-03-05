<?php
namespace Nicolasey\Forum\Http\Controller;

use Illuminate\Routing\Controller;
use Nicolasey\Forum\Models\Forum;
use Nicolasey\Forum\Models\Topic;

class TopicController extends Controller
{
    /**
     * Get all topics from a forum
     *
     * @param Forum $forum
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Forum $forum)
    {
        $forum->load("topics");
        return response()->json($forum);
    }

    /**
     * Show a topic
     *
     * @param Forum $forum
     * @param Topic $topic
     * @return Topic
     */
    public function show(Forum $forum, Topic $topic)
    {
        $topic->load("messages");
        return $topic;
    }

    /**
     * Store a topic
     *
     * @param Forum $forum
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store(Forum $forum)
    {
        try {
            $data = request()->only(['subject']);
            $data['forum_id'] = $forum->id;
            $topic = Topic::create($data);
            return response()->json($topic);
        } catch (\Exception $exception) {
            throw $exception;
        }

    }

    /**
     * Update topic
     *
     * @param Forum $forum
     * @param Topic $topic
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function update(Forum $forum, Topic $topic)
    {
        try {
            $data = request()->only(['subject', 'forum_id']);
            $topic->update($data);
            return response()->json($topic);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Delete topic
     *
     * @param Forum $forum
     * @param Topic $topic
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Forum $forum, Topic $topic)
    {
        try {
            $topic->delete();
            return response()->json(null, 204);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}