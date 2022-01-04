<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\PostFile;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {}

    public function show($key)
    {
        if ($img = PostFile::all()->where('key', $key)->first()) {

            $headers = array('Content-Type' => $img['type']);

            return response()->download(
                storage_path(
                    'app/files/img_' .
                    $img['key'] . '.' .
                    $img['extension']),
                $img['key'] . '.' .
                $img['extension'],
                $headers);
        }

        else {
            $response['response']['error'] = 'File not found.';
            return response()->json($response, 404);
        }
    }
}
