<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bearerToken = env('API_BEARER_TOKEN');
        $response = Http::withToken($bearerToken)->get('https://laraveltests.cactuscrm.gr/api/tags/getAll');
        $tags = $response->json();

        return Inertia::render('Tags\Index', compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Tags\Create');
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
        $response = Http::withToken($bearerToken)->post('https://laraveltests.cactuscrm.gr/api/tags', [
            'name' => $request->input('name'),
        ]);

        if ($response->successful()) {
            return redirect()->route('tags.index')->with('success', 'Tag created successfully.');
        } else {
            return back()->withInput()->withErrors(['error' => 'Failed to create tag.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bearerToken = env('API_BEARER_TOKEN');
        $response = Http::withToken($bearerToken)->get("https://laraveltests.cactuscrm.gr/api/tags/{$tagId}");
        $tag = $response->json();

        return Inertia::render('Tags\Show', compact('tag'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $bearerToken = env('API_BEARER_TOKEN');
        $response = Http::withToken($bearerToken)->get("https://laraveltests.cactuscrm.gr/api/tags/{$tagId}");
        $tag = $response->json();

        return Inertia::render('Tags\Edit', compact('tag'));
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
        $response = Http::withToken($bearerToken)->patch("https://laraveltests.cactuscrm.gr/api/tags/{$tagId}", [
            'name' => $request->input('name'),
        ]);

        if ($response->successful()) {
            return redirect()->route('tags.index')->with('success', 'Tag updated successfully.');
        } else {
            return back()->withInput()->withErrors(['error' => 'Failed to update tag.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bearerToken = env('API_BEARER_TOKEN');
        $response = Http::withToken($bearerToken)->delete("https://laraveltests.cactuscrm.gr/api/tags/{$tagId}");

        if ($response->successful()) {
            return redirect()->route('tags.index')->with('success', 'Tag deleted successfully.');
        } else {
            return back()->withErrors(['error' => 'Failed to delete tag.']);
        }
    }
}
