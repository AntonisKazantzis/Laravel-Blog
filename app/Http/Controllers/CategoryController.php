<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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

        return Inertia::render('Categories\Index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Categories\Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $bearerToken = env('API_BEARER_TOKEN');
        $response = Http::withToken($bearerToken)->post('https://laraveltests.cactuscrm.gr/api/categories', [
            'name' => $request->input('name'),
        ]);

        if ($response->successful()) {
            return redirect()->route('categories.index')->with('success', 'Category created successfully.');
        } else {
            return back()->withInput()->withErrors(['error' => 'Failed to create category.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bearerToken = env('API_BEARER_TOKEN');
        $response = Http::withToken($bearerToken)->get("https://laraveltests.cactuscrm.gr/api/categories/{$categoryId}");
        $category = $response->json();

        return Inertia::render('Categories\Show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $bearerToken = env('API_BEARER_TOKEN');
        $response = Http::withToken($bearerToken)->get("https://laraveltests.cactuscrm.gr/api/categories/{$categoryId}");
        $category = $response->json();

        return Inertia::render('Categories\Edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $bearerToken = env('API_BEARER_TOKEN');
        $response = Http::withToken($bearerToken)->patch("https://laraveltests.cactuscrm.gr/api/categories/{$categoryId}", [
            'name' => $request->input('name'),
        ]);

        if ($response->successful()) {
            return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
        } else {
            return back()->withInput()->withErrors(['error' => 'Failed to update category.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bearerToken = env('API_BEARER_TOKEN');
        $response = Http::withToken($bearerToken)->delete("https://laraveltests.cactuscrm.gr/api/categories/{$categoryId}");

        if ($response->successful()) {
            return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
        } else {
            return back()->withErrors(['error' => 'Failed to delete category.']);
        }
    }
}
