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
        $this->baseUrl = 'https://laraveltests.cactuscrm.gr/api';
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = Http::withToken($this->bearerToken)->get("$this->baseUrl/categories/getAll");

        $categories = $response->json();

        return Inertia::render('Categories/Index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $response = Http::withToken($this->bearerToken)->get("$this->baseUrl/categories/getAll");

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

            $response = Http::withToken($this->bearerToken)->post("$this->baseUrl/categories", [
                'name' => $request->input('name'),
            ]);

            if ($response->successful()) {
                return to_route('categories.index')->with(['messageTitle' => 'Created successfully.', 'messageBody' => 'Category has been created.']);
            }

            return back()->withInput()->withErrors(['messageTitle' => 'Error :/', 'messageBody' => 'Failed to create category.']);
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['messageTitle' => 'Error :/', 'messageBody' => 'Failed to create category.']);
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

            $response = Http::withToken($this->bearerToken)->patch("$this->baseUrl/categories/{$id}", [
                'name' => $request->input('name'),
            ]);

            if ($response->successful()) {
                return to_route('categories.index')->with(['messageTitle' => 'Updated successfully.', 'messageBody' => 'Category has been updated.']);
            }

            return back()->withInput()->withErrors(['messageTitle' => 'Error :/', 'messageBody' => 'Failed to update category.']);
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['messageTitle' => 'Error :/', 'messageBody' => 'Failed to update category.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $response = Http::withToken($this->bearerToken)->delete("$this->baseUrl/categories/{$id}");

            if ($response->successful()) {
                return to_route('categories.index')->with(['messageTitle' => 'Deleted successfully.', 'messageBody' => 'Category has been deleted.']);
            }

            return back()->withErrors(['messageTitle' => 'Error :/', 'messageBody' => 'Failed to delete category.']);
        } catch (\Exception $e) {
            return back()->withErrors(['messageTitle' => 'Error :/', 'messageBody' => 'Failed to delete category.']);
        }
    }
}
