<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    private $bearerToken;
    private $baseUrl;

    public function __construct()
    {
        $this->bearerToken = env('API_BEARER_TOKEN');
        $this->baseUrl = "https://laraveltests.cactuscrm.gr/api/posts";
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = Http::withToken($this->bearerToken)->get($this->baseUrl.'/getAll');

        $posts = $response->json();

        return Inertia::render('Posts/Index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $response = Http::withToken($this->bearerToken)->get($this->baseUrl.'/getAll');

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
                'image' => ['nullable', 'file', 'mimes:jpg,png,jpeg,gif', 'max:10240'],
                'category' => ['required', 'integer', 'max:255'],
                'subCategory' => ['required', 'integer', 'max:255'],
                'tags' => ['nullable', 'array', 'max:255'],
            ]);

            $tags = collect($request->input('tags'))->map(function ($tag) {
                return (int) $tag['id'];
            })->toArray();

            $image = $request->file('image');

            $response = Http::withToken($this->bearerToken)
                ->attach('image', $image->getContent(), $image->getClientOriginalName())
                ->asForm()
                ->post($this->baseUrl, [
                    'title' => $request->input('title'),
                    'body' => $request->input('body'),
                    'categoryId' => $request->input('category'),
                    'subCategoryId' => $request->input('subCategory'),
                    'tagsIds' => $tags,
                ]);

            if ($response->successful()) {
                return to_route('posts.index')->with('success', 'Post created successfully.');
            } else {
                return back()->withErrors(['error' => 'Failed to create post.']);
            }
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Failed to create post.']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $responsePost = Http::withToken($this->bearerToken)->get("$this->baseUrl/{$id}");
        $responsePosts = Http::withToken($this->bearerToken)->get($this->baseUrl.'/getAll');

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
                'image' => ['nullable', 'file', 'mimes:jpg,png,jpeg,gif', 'max:10240'],
                'category' => ['required', 'integer', 'max:255'],
                'subCategory' => ['required', 'integer', 'max:255'],
                'tags' => ['nullable', 'array', 'max:255'],
            ]);

            $tags = collect($request->input('tags'))->map(function ($tag) {
                return (int) $tag['id'];
            })->toArray();


            $image = $request->file('image');

            $response = Http::withToken($this->bearerToken)
                ->attach('image', $image->getContent(), $image->getClientOriginalName())
                ->asForm()
                ->patch("$this->baseUrl/{$id}", [
                    'title' => $request->input('title'),
                    'body' => $request->input('body'),
                    'categoryId' => $request->input('category'),
                    'subCategoryId' => $request->input('subCategory'),
                    'tagsIds' => $tags,
                ]);

            if ($response->successful()) {
                return to_route('posts.index')->with('success', 'Post updated successfully.');
            } else {
                return back()->withErrors(['error' => 'Failed to update post.']);
            }
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
            $response = Http::withToken($this->bearerToken)->delete("$this->baseUrl/{$id}");

            if ($response->successful()) {
                return to_route('posts.index')->with('success', 'Post deleted successfully.');
            } else {
                return back()->withErrors(['error' => 'Failed to delete post.']);
            }
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Failed to delete this post.']);
        }
    }
}
