<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\UserCredits;
use Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    public function index()
    {
        $featured = Post::latest()->first();
        if(!$featured) {
            return view('publicBlog', ['featured' => null, 'sidebarPosts' => collect(), 'recentPosts' => collect()]);
        }
        $sidebarPosts = Post::where('id', '!=', $featured->id)->take(3)->get();
        $recentPosts = Post::latest()->skip(1)->take(8)->get();

        return view('publicBlog', compact('featured', 'sidebarPosts', 'recentPosts'));
    }

    public function show(Request $request)
    {
        $request->validate([
            'slug' => 'required|string|exists:posts,slug'
        ]);
        $post = Post::where('slug', $request->slug)->firstOrFail();
        $related = Post::where('id', '!=', $post->id)->take(3)->get();

        return view('blog-detail', compact('post', 'related'));
    }

    // Show create post form
    public function create()
    {

       
        $headerData = array(); 
        if(Auth::check()){ 
            $userData  = Auth::user();
        }
        $userCredit              = UserCredits::getCreditPoint($userData ->id);
        $creditPoint            = ($userCredit) ? $userCredit->credits :0;
        $posts = Post::latest()->get();
        
            
        $headerData['creditPoint'] = $creditPoint??0; 
        return view('create-post')->with(compact('headerData','userData','posts'));
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return redirect()->back()->with('success', 'Post deleted successfully!');
    }

    // Store post
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'author' => 'nullable|string|max:255',
        ]);

        // Handle image upload
        $imagePath = null;

        if($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('assets/posts'), $filename);
            $imagePath = 'assets/posts/' . $filename;
        }

        Post::create([
            'title' => $request->title,
            'category' => $request->category,
            'slug' => Str::slug($request->title),
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'image' => $imagePath,
            'author' => $request->author,
        ]);

        return redirect()->route('posts.create')->with('success', 'Post created successfully!');
    }

    public function edit($id)
    {
        $headerData = array(); 
        if(Auth::check()){ 
            $userData  = Auth::user();
        }
        $userCredit              = UserCredits::getCreditPoint($userData ->id);
        $creditPoint            = ($userCredit) ? $userCredit->credits :0;
        $posts = Post::latest()->get();
        
            
        $headerData['creditPoint'] = $creditPoint??0; 
        $post = Post::findOrFail($id);
        return view('edit-post', compact('headerData','userData','post'));
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'nullable',
            'author' => 'nullable|string|max:255',
        ]);
        
        $post->update($request->only([
            'title', 'category', 'excerpt', 'content', 'author'
        ]));

        return redirect()
            ->route('posts.create')
            ->with('success', 'Post updated successfully!');
    }
}
