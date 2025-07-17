<?php

namespace App\Http\Controllers\API;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    public function store(Request $request, Post $post) {
        $request->validate(['text' => 'required']);

        $comment = Comment::create([
            'text' => $request->text,
            'post_id' => $post->id,
            'user_id' => $request->user()->id
        ]);

        return response()->json($comment, 201);
    }

    public function destroy(Comment $comment) {
        $this->authorize('delete', $comment);
        $comment->delete();

        return response()->json(['message' => 'Comment deleted']);
    }
}
