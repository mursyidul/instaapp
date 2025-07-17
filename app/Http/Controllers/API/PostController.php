<?php

namespace App\Http\Controllers\API;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PostController extends BaseController
{
    use AuthorizesRequests;

    public function index() {
        return Post::with('user', 'likes', 'comments')->latest()->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'caption' => 'required|string',
            'image' => 'nullable|image|max:2048'
        ]);

        try {
            $path = $request->file('image')?->store('images', 'public');

            $post = Post::create([
                'user_id' => $request->user()->id,
                'caption' => $request->caption,
                'image' => $path
            ]);

            return $this->sendResponse($post, 'Postingan berhasil dibuat.');
        } catch (\Exception $e) {
            return $this->sendError('Gagal membuat postingan: ' . $e->getMessage(), 500);
        }
    }


    public function destroy(Post $post) {
        $this->authorize('delete', $post);
        if ($post->image) Storage::disk('public')->delete($post->image);
        $post->delete();

        return $this->sendResponse(null, 'Post berhasil dihapus');
    }
}
