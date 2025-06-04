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
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Matrix\Decomposition\QR;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $array_filter = request()->only([
            'name',
            'price',
            'stock_quantity',
            'category_id',
        ]);
        if (count($array_filter) == 0) {
            $array_filter = [
                'name' => '',
                'price' => '',
                'stock_quantity' => '',
                'category_id' => '',
            ];
        }
        $products = Product::when(count($array_filter) > 0, function ($q) use ($array_filter) {
            foreach ($array_filter as $column => $value) {
                if ($column === 'category_id' && $value != '') {
                    $q->where($column, $value);
                } elseif ($column === 'price' && $value != '') {
                    $priceData = explode('-', $value);
                    if (count($priceData) != 1) {
                        $q->whereBetween('price', [$priceData[0], $priceData[1]]);
                    } else {
                        $q->where($column, '>=', substr($priceData[0], 1));
                    }
                } else {
                    $q->where($column, 'LIKE', '%' . $value . '%');
                }
            }
        })->with('category')->get();
        if (!Cache::has('products_' . $array_filter['name'] . '_' . $array_filter['price'] . '_' . $array_filter['stock_quantity'] . '_' . $array_filter['category_id'])) {
            $productData = Cache::put('products_' . $array_filter['name'] . '_' . $array_filter['price'] . '_' . $array_filter['stock_quantity'] . '_' . $array_filter['category_id'], $products, 100);
        }
        $productData = Cache::get('products_' . $array_filter['name'] . '_' . $array_filter['price'] . '_' . $array_filter['stock_quantity'] . '_' . $array_filter['category_id']);

        return new ProductCollection($productData);
        // return new ProductCollection(Cache::remember('products', 10, function () {
        //     return Product::with('category')->paginate();
        // }));
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
        $url = 'google.com'; //change for this product
        $product=Product::with('category')->findOrFail($id);
        $qr=base64_encode(QrCode::format('png')->size(50)->generate($url));
        return (new ProductResource($product))->additional([
            'qr'=>"data:image/png;base64,{$qr}"
        ]);
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
