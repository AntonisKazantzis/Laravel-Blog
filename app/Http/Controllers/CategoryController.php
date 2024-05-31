<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bearerToken = env('API_BEARER_TOKEN');
        $response = Http::withToken($bearerToken)->get('https://laraveltests.cactuscrm.gr/api/categories/getAll');

        $categories = $response->json();

        return Inertia::render('Categories/Index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bearerToken = env('API_BEARER_TOKEN');
        $response = Http::withToken($bearerToken)->get('https://laraveltests.cactuscrm.gr/api/categories/getAll');

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

            $bearerToken = env('API_BEARER_TOKEN');
            $response = Http::withToken($bearerToken)->post('https://laraveltests.cactuscrm.gr/api/categories', [
                'name' => $request->input('name'),
            ]);

            $response->json();

            return to_route('posts.index')->with('success', 'Category created successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Failed to create category.']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $bearerToken = env('API_BEARER_TOKEN');
        $response = Http::withToken($bearerToken)->get("https://laraveltests.cactuscrm.gr/api/categories/{$id}");

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

            $bearerToken = env('API_BEARER_TOKEN');
            $response = Http::withToken($bearerToken)->patch("https://laraveltests.cactuscrm.gr/api/categories/{$id}", [
                'name' => $request->input('name'),
            ]);

            $response->json();

            return to_route('posts.index')->with('success', 'Category created successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Failed to create category.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bearerToken = env('API_BEARER_TOKEN');
        $response = Http::withToken($bearerToken)->delete("https://laraveltests.cactuscrm.gr/api/categories/{$id}");

        if ($response->successful()) {
            return to_route('categories.index')->with('success', 'Category deleted successfully.');
        } else {
            return back()->withErrors(['error' => 'Failed to delete category.']);
        }
    }
}
