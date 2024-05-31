<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

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
        $bearerToken = env('API_BEARER_TOKEN');
        $response = Http::withToken($bearerToken)->get('https://laraveltests.cactuscrm.gr/api/posts/getAll');

        $posts = $response->json();

        return Inertia::render('Posts/Create', compact('posts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'body' => ['required', 'string', 'max:65535'],
                'image' => ['nullable', 'mimes:jpg,png,jpeg,gif', 'max:10240'],
                'category' => ['required', 'integer', 'max:255'],
                'subCategory' => ['required', 'integer', 'max:255'],
                'tags' => ['nullable', 'array', 'max:255'],
            ]);

            $tags = collect($request->input('tags'))->map(function ($tag) {
                return (int) $tag['id'];
            })->toArray();

            $bearerToken = env('API_BEARER_TOKEN');
            $response = Http::withToken($bearerToken)->post('https://laraveltests.cactuscrm.gr/api/posts', [
                'title' => $request->input('title'),
                'body' => $request->input('body'),
                'image' => $request->input('image'),
                'categoryId' => $request->input('category'),
                'subCategoryId' => $request->input('subCategory'),
                'tagsIds' => $tags,
            ]);

            $response->json();

            return to_route('posts.index')->with('success', 'Post created successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Failed to create post.']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $bearerToken = env('API_BEARER_TOKEN');
        $responsePost = Http::withToken($bearerToken)->get("https://laraveltests.cactuscrm.gr/api/posts/{$id}");
        $responsePosts = Http::withToken($bearerToken)->get('https://laraveltests.cactuscrm.gr/api/posts/getAll');

        $post = $responsePost->json();
        $posts = $responsePosts->json();

        return Inertia::render('Posts/Edit', compact('post', 'posts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'body' => ['required', 'string', 'max:65535'],
                'image' => ['nullable', 'mimes:jpg,png,jpeg,gif', 'max:10240'],
                'category' => ['required', 'integer', 'max:255'],
                'subCategory' => ['required', 'integer', 'max:255'],
                'tags' => ['nullable', 'array', 'max:255'],
            ]);

            $tags = collect($request->input('tags'))->map(function ($tag) {
                return (int) $tag['id'];
            })->toArray();

            $bearerToken = env('API_BEARER_TOKEN');
            $response = Http::withToken($bearerToken)->patch("https://laraveltests.cactuscrm.gr/api/posts/{$id}", [
                'title' => $request->input('title'),
                'body' => $request->input('body'),
                'image' => $request->input('image'),
                'categoryId' => $request->input('category'),
                'subCategoryId' => $request->input('subCategory'),
                'tagsIds' => $tags,
            ]);

            $response->json();

            return to_route('posts.index')->with('success', 'Post updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Failed to update post.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $bearerToken = env('API_BEARER_TOKEN');
            $response = Http::withToken($bearerToken)->delete("https://laraveltests.cactuscrm.gr/api/posts/{$id}");

            return to_route('posts.index')->with('success', 'Post deleted successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Failed to delete this post.']);
        }
    }
}
