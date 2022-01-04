<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\PostFile;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {}

    public function showAll() {
        $posts = Post::all()->sortBy('created_at')->reverse()->values();

        foreach($posts as $post) {
            $post['comments'] = Comment::all()->where('post_id', $post['id'])->values();
            $post['files'] = PostFile::all()->where('post_id', $post['id'])->values();
        }

        return response()->json($posts, 200);
    }

    public function add(Request $request) {
        $post_title = $request->input("post_title");
        $post_info = $request->input("post_info");
        $post_files = $request->file('post_files');

        $post = new Post();
        $post->post_title = $post_title;
        $post->post_info = $post_info;
        $post->save();

        foreach ($post_files as $file) {
            $post_file = new PostFile();

            $post_file->post_id = $post['id'];
            $post_file->name = $file->getClientOriginalName();
            $post_file->extension = $file->getClientOriginalExtension();
            $post_file->key = 'key';
            $post_file->type = $file->getMimeType();
            $post_file->size = $file->getSize();
            $post_file->save();

            $post_file->key = md5('img_' . $post_file['id']);
            $post_file->save();

            $file->move(storage_path('app/files'), 'img_' . $post_file['key'] . '.' . $file->getClientOriginalExtension());
        }

        return response()->json($post, 200);
    }

    public function edit(Request $request, $post_id) {
        $post_title = $request->input("post_title");
        $post_info = $request->input("post_info");

        $post = Post::find($post_id);
        $post->post_title = $post_title;
        $post->post_info = $post_info;
        $post->save();

        return response()->json($post, 200);
    }

    public function show($post_id) {
        $post = Post::find($post_id);

        $post['comments'] = Comment::all()->where('post_id', $post_id)->values();
        $post['files'] = PostFile::all()->where('post_id', $post['id'])->values();

        return response()->json($post, 200);
    }

    public function delete($post_id) {
        $post = Post::find($post_id);
        $post->delete();
        return response()->json(["message" => "Succesfull deleted post"], 200);
    }
}
