<?php

namespace App\Http\Controllers\API;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LikeController extends Controller
{
    public function toggle(Post $post) {
        $userId = auth()->id();
        $like = Like::where('user_id', $userId)->where('post_id', $post->id)->first();

        if ($like) {
            $like->delete();
            return response()->json(['message' => 'Unliked']);
        } else {
            Like::create(['user_id' => $userId, 'post_id' => $post->id]);
            return response()->json(['message' => 'Liked']);
        }
    }
}
