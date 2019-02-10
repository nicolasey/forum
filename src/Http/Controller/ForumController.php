<?php
namespace Nicolasey\Forum\Http\Controller;

use Illuminate\Routing\Controller;
use Nicolasey\Forum\Models\Forum;

class ForumController extends Controller
{
    /**
     * List core forums
     *
     * @return mixed
     */
    public function index()
    {
        return Forum::findByParent(null);
    }

    /**
     * Show a forum
     *
     * @param Forum $forum
     * @return Forum
     */
    public function show(Forum $forum)
    {
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
            $forum = Forum::create($data);
            return $forum;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Update a forum
     *
     * @param Forum $forum
     * @throws \Exception
     */
    public function update(Forum $forum)
    {
        $data = request()->only(['name', "content"]);
        try {
            $forum->update($data);
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
            $forum->delete();
            return response()->json();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}