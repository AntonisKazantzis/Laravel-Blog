<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bearerToken = env('API_BEARER_TOKEN');
        $response = Http::withToken($bearerToken)->get('https://laraveltests.cactuscrm.gr/api/posts/getAll');
        $posts = $response->json();

        return Inertia::render('Posts/Index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Posts\Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:65535'],
            'categoryId' => ['required', 'integer', 'max:255'],
            'subCategoryId' => ['required', 'integer', 'max:255'],
            'image' => ['nullable', 'mimes:jpg,png,jpeg,gif', 'max:10240'],
            'tagIds' => ['nullable', 'array', 'max:255'],
        ]);

        $bearerToken = env('API_BEARER_TOKEN');
        $response = Http::withToken($bearerToken)->post('https://laraveltests.cactuscrm.gr/api/posts', [
            'name' => $request->input('name'),
        ]);

        if ($response->successful()) {
            return redirect()->route('posts.index')->with('success', 'Post created successfully.');
        } else {
            return back()->withInput()->withErrors(['error' => 'Failed to create post.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bearerToken = env('API_BEARER_TOKEN');
        $response = Http::withToken($bearerToken)->get("https://laraveltests.cactuscrm.gr/api/posts/{$postId}");
        $post = $response->json();

        return Inertia::render('Posts\Show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $bearerToken = env('API_BEARER_TOKEN');
        $response = Http::withToken($bearerToken)->get("https://laraveltests.cactuscrm.gr/api/posts/{$postId}");
        $post = $response->json();

        return Inertia::render('Posts\Edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:65535'],
            'categoryId' => ['required', 'integer', 'max:255'],
            'subCategoryId' => ['required', 'integer', 'max:255'],
            'image' => ['nullable', 'mimes:jpg,png,jpeg,gif', 'max:10240'],
            'tagIds' => ['nullable', 'array', 'max:255'],
        ]);

        $bearerToken = env('API_BEARER_TOKEN');
        $response = Http::withToken($bearerToken)->patch("https://laraveltests.cactuscrm.gr/api/posts/{$postId}", [
            'name' => $request->input('name'),
        ]);

        if ($response->successful()) {
            return redirect()->route('posts.index')->with('success', 'Post updated successfully.');
        } else {
            return back()->withInput()->withErrors(['error' => 'Failed to update post.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bearerToken = env('API_BEARER_TOKEN');
        $response = Http::withToken($bearerToken)->delete("https://laraveltests.cactuscrm.gr/api/posts/{$postId}");

        if ($response->successful()) {
            return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
        } else {
            return back()->withErrors(['error' => 'Failed to delete post.']);
        }
    }
}
