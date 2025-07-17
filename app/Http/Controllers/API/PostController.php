<?php

namespace App\Http\Controllers\API;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index() {
        return Post::with('user', 'likes', 'comments')->latest()->get();
    }

    public function store(Request $request) {
        $request->validate([
            'caption' => 'required|string',
            'image' => 'nullable|image|max:2048'
        ]);

        $path = $request->file('image')?->store('images', 'public');

        $post = Post::create([
            'user_id' => $request->user()->id,
            'caption' => $request->caption,
            'image' => $path
        ]);

        return response()->json($post, 201);
    }

    public function destroy(Post $post) {
        $this->authorize('delete', $post);
        if ($post->image) Storage::disk('public')->delete($post->image);
        $post->delete();

        return response()->json(['message' => 'Post deleted']);
    }
}
