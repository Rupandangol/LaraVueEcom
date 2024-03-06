<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CategoryCreateRequest;
use App\Http\Requests\V1\CategoryUpdateRequest;
use App\Http\Resources\V1\CategoryCollection;
use App\Http\Resources\V1\CategoryResource;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new CategoryCollection(Cache::remember('categories',3, function () {
            return Category::with('products')->get();
        }));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryCreateRequest $request)
    {
        $data['name'] = $request->name;
        $data['description'] = $request->description;
        $data['slug'] = Str::of($request->name)->slug('-');
        return Category::create($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // return Category::findOrFail($id)->getProducts;

        return new CategoryResource(Category::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryUpdateRequest $request, string $id)
    {
        $category = Category::findOrFail($id);
        return $category->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Category::findOrFail($id)->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Deleted Successfully'
        ], 200);
    }
}
