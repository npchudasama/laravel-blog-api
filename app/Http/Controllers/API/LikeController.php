<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\Like;

class LikeController extends Controller
{
    
    public function toggle($id)
    {
        $blog = Blog::findOrFail($id);

        $like = $blog->likes()->where('user_id', auth()->id())->first();

        if ($like) {
            $like->delete();
            return response()->json(['message' => 'Unliked']);
        } else {
            $blog->likes()->create([
                'user_id' => auth()->id(),
            ]);
            return response()->json(['message' => 'Liked']);
        }
    }
}
