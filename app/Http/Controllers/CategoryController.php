<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class CategoryController extends Controller
{
    private $bearerToken;

    private $baseUrl;

    public function __construct()
    {
        $this->bearerToken = env('API_BEARER_TOKEN');
        $this->baseUrl = 'https://laraveltests.cactuscrm.gr/api/categories';
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = Http::withToken($this->bearerToken)->get($this->baseUrl.'/getAll');

        $categories = $response->json();

        return Inertia::render('Categories/Index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $response = Http::withToken($this->bearerToken)->get($this->baseUrl.'/getAll');

        $categories = $response->json();

        return Inertia::render('Categories/Create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
            ]);

            $response = Http::withToken($this->bearerToken)->post($this->baseUrl, [
                'name' => $request->input('name'),
            ]);

            if ($response->successful()) {
                return to_route('categories.index');
            }

            return back();
        } catch (\Exception $e) {
            return back()->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $response = Http::withToken($this->bearerToken)->get("$this->baseUrl/{$id}");

        $category = $response->json();

        return Inertia::render('Categories/Edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
            ]);

            $response = Http::withToken($this->bearerToken)->patch("$this->baseUrl/{$id}", [
                'name' => $request->input('name'),
            ]);

            if ($response->successful()) {
                return to_route('categories.index');
            }

            return back();
        } catch (\Exception $e) {
            return back()->withInput();
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
                return to_route('categories.index');
            }

            return back();
        } catch (\Exception $e) {
            return back()->withInput();
        }
    }
}
