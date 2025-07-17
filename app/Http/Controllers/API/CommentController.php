<?php

namespace App\Http\Controllers\API;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CommentController extends BaseController
{
    use AuthorizesRequests;

    public function store(Request $request, Post $post) {
        $request->validate(['text' => 'required']);

        $comment = Comment::create([
            'text' => $request->text,
            'post_id' => $post->id,
            'user_id' => $request->user()->id
        ]);
        return $this->sendResponse($comment, 'Komentar berhasil ditambahkan');
    }

    public function destroy(Comment $comment) {
        $this->authorize('delete', $comment);
        $comment->delete();

        return $this->sendResponse(null, 'Comment berhasil dihapus');
    }
}
