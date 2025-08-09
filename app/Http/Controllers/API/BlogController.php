<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    
public function index(Request $request)
{
    $query = Blog::withCount('likes')->with('user');

    
    if ($request->search) {
        $searchTerm = $request->search;
        $query->where(function ($q) use ($searchTerm) {
            $q->where('title', 'like', "%{$searchTerm}%")
              ->orWhere('description', 'like', "%{$searchTerm}%");
        });
    }

    
    if ($request->filter == 'most_liked') {
        $query->orderBy('likes_count', 'desc');
    }
    elseif ($request->filter == 'latest') {
        $query->latest();
    }

    
    $blogs = $query->paginate(5);

    
    $blogs->getCollection()->transform(function ($blog) {
        $blog->liked_by_user = $blog->likes->where('user_id', Auth::id())->count() > 0;
        return $blog;
    });

    return response()->json($blogs);
}






    
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required',
            'description' => 'required',
            'image'       => 'nullable|image',
        ]);

        $path = $request->file('image')?->store('blogs', 'public');

        $blog = Blog::create([
            'title'       => $request->title,
            'description' => $request->description,
            'image'       => $path,
            'user_id'     => auth()->id(),
        ]);

        return response()->json([
            'message' => 'Blog created successfully',
            'data'    => $blog,
        ]);
    }

    
    public function show($id)
    {
        $blog = Blog::with('user')->findOrFail($id);
        return response()->json($blog);
    }

   
   public function update(Request $request, $id)
{
    $blog = Blog::findOrFail($id);

    if ($blog->user_id != auth()->id()) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $blog->title = $request->title ?? $blog->title;
    $blog->description = $request->description ?? $blog->description;

    if ($request->hasFile('image')) {
        $blog->image = $request->file('image')->store('blogs', 'public');
    }

    $blog->save();

    return response()->json(['message' => 'Blog updated']);
}


   
    public function destroy($id)
{
    $blog = Blog::findOrFail($id);

    if ($blog->user_id != auth()->id()) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $blog->delete();

    return response()->json(['message' => 'Blog deleted']);
}
}
