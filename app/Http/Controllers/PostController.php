<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class PostController extends Controller
{
    private $bearerToken;

    private $baseUrl;

    public function __construct()
    {
        $this->bearerToken = env('API_BEARER_TOKEN');
        $this->baseUrl = 'https://laraveltests.cactuscrm.gr/api/posts';
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = Http::withToken($this->bearerToken)->get($this->baseUrl . '/getAll');

        $posts = $response->json();

        return Inertia::render('Posts/Index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $response = Http::withToken($this->bearerToken)->get($this->baseUrl . '/getAll');

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

            $params = [
                'title' => $request->input('title'),
                'body' => $request->input('body'),
                'categoryId' => $request->input('category'),
                'subCategoryId' => $request->input('subCategory'),
                'tagsIds' => $tags,
            ];

            $response = Http::withToken($this->bearerToken)
                ->attach('image', file_get_contents($image->getPathname()), $image->getClientOriginalName())
                ->asMultipart()
                ->post($this->baseUrl, $this->transformMultiFormData($params));

            if ($response->successful()) {
                return to_route('posts.index')->with(['messageTitle' => 'Created successfully.', 'messageBody' => 'Post has been create.']);
            }

            \Log::error('API Error: ' . $response->status() . ' - ' . $response->body());

            return back()->withInput()->withErrors(['messageTitle' => 'Error :/', 'messageBody' => 'Failed to create post.']);
        } catch (\Exception $e) {
            \Log::info($e->getMessage());

            return back()->withInput()->withErrors(['messageTitle' => 'Error :/', 'messageBody' => 'Failed to create post.']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $responsePost = Http::withToken($this->bearerToken)->get("$this->baseUrl/{$id}");
        $responsePosts = Http::withToken($this->bearerToken)->get($this->baseUrl . '/getAll');

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

            $params = [
                'title' => $request->input('title'),
                'body' => $request->input('body'),
                'categoryId' => $request->input('category'),
                'subCategoryId' => $request->input('subCategory'),
                'tagsIds' => $tags,
            ];

            $response = Http::withToken($this->bearerToken)
                ->attach('image', file_get_contents($image->getPathname()), $image->getClientOriginalName())
                ->asMultipart()
                ->post("$this->baseUrl/{$id}?_method=PATCH", $this->transformMultiFormData($params));

            if ($response->successful()) {
                return to_route('posts.index')->with(['messageTitle' => 'Updated successfully.', 'messageBody' => 'Post has been updated.']);
            }

            \Log::error('API Error: ' . $response->status() . ' - ' . $response->body());

            return back()->withInput()->withErrors(['messageTitle' => 'Error :/', 'messageBody' => 'Failed to update post.']);
        } catch (\Exception $e) {
            \Log::info($e->getMessage());

            return back()->withInput()->withErrors(['messageTitle' => 'Error :/', 'messageBody' => 'Failed to update post.']);
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
                return to_route('posts.index')->with(['messageTitle' => 'Deleted successfully.', 'messageBody' => 'Post has been deleted.']);
            }

            return back()->withErrors(['messageTitle' => 'Error :/', 'messageBody' => 'Failed to delete post.']);
        } catch (\Exception $e) {
            return back()->withErrors(['messageTitle' => 'Error :/', 'messageBody' => 'Failed to delete post.']);
        }
    }

    private function transformMultiFormData($data)
    {
        $output = [];

        foreach ($data as $key => $value) {
            if (!is_array($value)) {
                $output[] = ['name' => $key, 'contents' => $value];
                continue;
            }

            foreach ($value as $multiKey => $multiValue) {
                $multiName = $key . '[' . $multiKey . ']' . (is_array($multiValue) ? '[' . key($multiValue) . ']' : '') . '';
                $output[] = ['name' => $multiName, 'contents' => (is_array($multiValue) ? reset($multiValue) : $multiValue)];
            }
        }
        return $output;
    }
}
