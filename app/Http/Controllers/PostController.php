<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class PostController extends Controller
{
    private $bearerToken;

    private $baseUrl;

    public function __construct()
    {
        $this->bearerToken = env('API_BEARER_TOKEN');
        $this->baseUrl = 'https://laraveltests.cactuscrm.gr/api';
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = 10;

        $response = Http::withToken($this->bearerToken)->get("$this->baseUrl/posts/getAll");
        $data = $response->body();

        $res = collect(json_decode($data, true));

        $paginator = new LengthAwarePaginator(
            $res->forPage($page, $perPage),
            $res->count(),
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => array_merge($request->query(), ['page' => $page]),
            ]
        );

        return Inertia::render('Posts/Index', [
            'posts' => $paginator,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $response = Http::withToken($this->bearerToken)->get("$this->baseUrl/posts/getAll");

        $posts = $response->json();

        return Inertia::render('Posts/Create', compact('posts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $image = $request->file('image');

            $rules = [
                'title' => ['required', 'string', 'max:255'],
                'body' => ['required', 'string', 'max:65535'],
                'category' => ['required', 'integer', 'max:255'],
                'subCategory' => ['required', 'integer', 'max:255'],
                'tags' => ['nullable', 'array', 'max:255'],
            ];

            if ($image) {
                $rules['image'] = ['nullable', 'file', 'mimes:jpg,png,jpeg,gif', 'max:10240'];
            }

            $request->validate($rules);

            $tags = array_map('intval', (array) $request->input('tags'));

            $params = [
                'title' => $request->input('title'),
                'body' => $request->input('body'),
                'categoryId' => $request->input('category'),
                'subCategoryId' => $request->input('subCategory'),
                'tagsIds' => $tags,
            ];

            $response = Http::withToken($this->bearerToken);

            if ($image) {
                $response = $response->attach('image', file_get_contents($image->getPathname()), $image->getClientOriginalName())
                    ->asMultipart()
                    ->post("$this->baseUrl/posts", $this->transformMultiFormData($params));
            } else {
                $response = $response->post("$this->baseUrl/posts", $params);
            }

            if ($response->successful()) {
                return to_route('posts.index')->with(['messageTitle' => 'Created successfully.', 'messageBody' => 'Post has been create.']);
            }

            return back()->withInput()->withErrors(['messageTitle' => 'Error :/', 'messageBody' => 'Failed to create post.']);
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['messageTitle' => 'Error :/', 'messageBody' => 'Failed to create post.']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $responsePost = Http::withToken($this->bearerToken)->get("$this->baseUrl/posts/{$id}");
        $responsePosts = Http::withToken($this->bearerToken)->get("$this->baseUrl/posts/getAll");

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
            $image = $request->file('image');

            $rules = [
                'title' => ['required', 'string', 'max:255'],
                'body' => ['required', 'string', 'max:65535'],
                'category' => ['required', 'integer', 'max:255'],
                'subCategory' => ['required', 'integer', 'max:255'],
                'tags' => ['nullable', 'array', 'max:255'],
            ];

            if ($image) {
                $rules['image'] = ['nullable', 'file', 'mimes:jpg,png,jpeg,gif', 'max:10240'];
            }

            $request->validate($rules);

            $tags = array_map('intval', (array) $request->input('tags'));

            $params = [
                'title' => $request->input('title'),
                'body' => $request->input('body'),
                'categoryId' => $request->input('category'),
                'subCategoryId' => $request->input('subCategory'),
                'tagsIds' => $tags,
            ];

            $isDummyImage = strpos($request['image'], 'https://dummyimage.com/') !== false;

            $response = Http::withToken($this->bearerToken);

            if ($request['image'] && !$isDummyImage) {
                $imagePath = $image ? $image->getPathname() : $request['image'];
                $imageName = $image ? $image->getClientOriginalName() : basename(parse_url($request['image'], PHP_URL_PATH));

                $response = $response->withToken($this->bearerToken)->attach('image', file_get_contents($imagePath), $imageName)
                    ->asMultipart()
                    ->post("$this->baseUrl/posts/{$id}?_method=PATCH", $this->transformMultiFormData($params));
            } else {
                $response = $response->withToken($this->bearerToken)->patch("$this->baseUrl/posts/{$id}", $params);
            }

            if ($response->successful()) {
                return to_route('posts.index')->with(['messageTitle' => 'Updated successfully.', 'messageBody' => 'Post has been updated.']);
            }

            return back()->withInput()->withErrors(['messageTitle' => 'Error :/', 'messageBody' => 'Failed to update post.']);
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['messageTitle' => 'Error :/', 'messageBody' => 'Failed to update post.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $response = Http::withToken($this->bearerToken)->delete("$this->baseUrl/posts/{$id}");

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
