<?php
namespace Nicolasey\Forum\Http\Controllers;


use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Nicolasey\Forum\Models\Forum;
use Nicolasey\Forum\Events\ForumMoved;

class ForumController extends Controller
{
    use ValidatesRequests;

    public function index() 
    {
        $parent = request()->get('parent');

        return Forum::where('parent_id', $parent)->get();
    }

    /**
     * Store a forum
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store()
    {
        $data = request()->only(["name", "content", "parent"]);
        $this->validate(request(), [
            "name" => "string|required|min:3|unique:forum_forums"
        ]);

        try {
            $forum = Forum::create($data);
            return response()->json($forum, 204);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Update a forum
     *
     * @param Forum $forum
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function update(Forum $forum)
    {
        $data = request()->only(["name", "content", "parent"]);
        $this->validate(request(), [
            "name" => "string|required|min:3|unique:forum_forums"
        ]);

        // update
        try {
            $forum->update($data);

            // check if parent has changed
            if($forum->hasMoved())
            {
                event(new ForumMoved($forum));
            }

            return response()->json($forum);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Display a forum
     *
     * @param Forum $forum
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Forum $forum)
    {
        return response()->json($forum);
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