<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ProductCreateRequest;
use App\Http\Requests\V1\ProductUpdateRequest;
use App\Http\Resources\V1\ProductCollection;
use App\Http\Resources\V1\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new ProductCollection(Product::with('category')->paginate());
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductCreateRequest $request)
    {
        return Product::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return new ProductResource(Product::with('category')->findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductUpdateRequest $request, string $id)
    {
        $product= Product::findOrFail($id);
        return $product->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return Product::findOrfail($id)->delete();
    }

    /**
     * Related products from storage
     */
    public function related()
    {
        return new ProductCollection(Product::orderByRaw('RAND()')->take(4)->get());
    }
}
