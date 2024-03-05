<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;

class BaseController extends Controller
{
    public function index()
    {
        $posts = Post::all();
        return response()->json($posts);
    }
    public function show(Post $post)
    {
        return response()->json($post);
    }

    public function create(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => 'Validation failed', 'messages' => $validator->messages()], 422);
        }

        $data = $validator->validated();

        $data['user_id'] = auth()->user()->id;
        $post = Post::create($data);
        return response()->json($post);
    }

    public function update(Post $post,Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => 'Validation failed', 'messages' => $validator->messages()], 422);
        }

        $data = $validator->validated();
        $data['user_id'] = 1;

        $post->update($data);
        return response()->json($post);
    }

    public function delete(Post $post)
    {
        $post->delete();
        return response()->json('The post has been deleted');
    }

    public function personalAccess(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credtiantials are incorrect.']
            ]);
        }

        return ['token' => $user->createToken($request->device_name)->plainTextToken];
    }
}
