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
        $array_filters = request()->only([
            'name'
        ]);
        if (count($array_filters) == 0) {
            $array_filters = [
                'name' => ''
            ];
        }
        $categories = Category::when(count($array_filters) > 0, function ($q) use ($array_filters) {
            foreach ($array_filters as $column => $value) {
                if ($value != '') {
                    $q->where($column, 'LIKE', '%' . $value . '%');
                }
            }
        })->with('products')->get();
        if (!Cache::has('categories_' . $array_filters['name'])) {
            $cachedData = Cache::put('categories_' . ($array_filters['name'] ?? ''), $categories, 3);
        }
        $cachedData = Cache::get('categories_' . ($array_filters['name'] ?? ''));
        return new CategoryCollection($cachedData);
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
