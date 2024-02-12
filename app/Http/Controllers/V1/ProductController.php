<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ProductCreateRequest;
use App\Http\Requests\V1\ProductUpdateRequest;
use App\Http\Resources\V1\ProductCollection;
use App\Http\Resources\V1\ProductResource;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

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
        $data['name'] = $request->name;
        $data['description'] = $request->description;
        $data['category_id'] = $request->category_id;
        $data['price'] = $request->price;
        $data['stock_quantity'] = $request->stock_quantity;
        if ($request->hasFile('image')) {
            $imageName =  time() . '.' . $request->file('image')->extension();
            // $path = $request->file('image')->storeAs('public/images/', $imageName);
            $manager = new ImageManager(new Driver());
            $img = $manager->read($request->file('image'))->resize(400, 300)->toJpeg(80)->save('storage/images/' . $imageName);
            $data['image'] =  $imageName;
        }
        return Product::create($data);
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
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);
        $data['name'] = $request->name;
        $data['description'] = $request->description;
        $data['category_id'] = $request->category_id;
        $data['price'] = $request->price;
        $data['stock_quantity'] = $request->stock_quantity;
        if ($request->hasFile('image')) {
            $this->removeImage($product->image);
            $imageName =  time() . '.' . $request->file('image')->extension();
            // $path = $request->file('image')->storeAs('public/images/', $imageName);
            $manager = new ImageManager(new Driver());
            $img = $manager->read($request->file('image'))->resize(400, 300)->toJpeg(80)->save('storage/images/' . $imageName);
            $data['image'] =  $imageName;
        }
        return $product->update($data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $product = Product::findOrfail($id);
            $this->removeImage($product->image);
            $product->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Deleted Successfully'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Related products from storage
     */
    public function related()
    {
        return new ProductCollection(Product::orderByRaw('RAND()')->take(4)->get());
    }
    /**
     * Delete image
     */

    public function removeImage($imageName)
    {
        $imagePath = 'storage/images/' . $imageName;
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }
}
