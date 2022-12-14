<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostCollection;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $posts = Post::all();
            if ($posts) {
                return PostCollection::collection($posts);
            }
            return response(status: 404);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage()
            ]);
        }
    }



    public function store(Request $request)
    {

        try {
            $validation = Validator::make($request->all(), [
                'title' => ['required', 'string'],
                'post' => ['required', 'string', 'min:100'],
                'slug' => ['required', 'string'],
                'author_id' => ['required', 'int']
            ]);

            if ($validation->fails()) {
                return response()->json([
                    'error' => $validation->errors()->all()
                ]);
            } else {
                $post = new Post();

                $result = Post::create([
                    $post->title = $request->title,
                    $post->post = $request->post,
                    $post->slug = $request->slug,
                    $post->author_id = $request->author_id,
                ]);
                \Log::error(json_encode($result));



                if ($result) {
                    return response(status: 201);
                }
            }

            return;
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage()
            ]);
        }
    }


    public function show(int $id)
    {

        try {
            $post = Post::findOrFail($id);
            if ($post) {
                return $post;
                return PostCollection::collection($post);
            }
        } catch (\Throwable $th) {

            // \Log::error(json_encode($th));
            return response()->json([
                'error' => $th->getMessage()
            ]);
        }
    }

    public function edit(Post $Post)
    {
        //
    }

    public function update(Request $request, int $id)
    {
        try {
            $post = Post::findOrFail($id);
            $validation = Validator::make($request->all(), [
                'title' => ['required', 'string', 'max:20', 'min:10'],
                'post' => ['required', 'string', 'min:100'],
                'slug' => ['required', 'string'],
                'author_id' => ['required', 'int']
            ]);

            if ($validation->fails()) {
                return response()->json([
                    'error' => $validation->errors()->all()
                ]);
            } else {
                $post->post = $request->post;
                $post->slug = $request->slug;
                $post->author_id = $request->author_id;
                $result  = $post->save();
                if ($result) {
                    return response()->json([
                        "status" => 200,
                        "message" => "post updated succesfully"
                    ]);
                }
            }

            return;
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage()
            ]);
        }
    }


    public function destroy(int $id)
    {
        try {
            $post = Post::findOrFail($id);
            if ($post) {
                return response()->json([
                    "Message" => "Post was deleted",
                    "post" => $post
                ]);
            }
        } catch (\Throwable $e) {
            return response()->json([
                "error" => $e->getMessage()
            ]);
        }
    }
}
