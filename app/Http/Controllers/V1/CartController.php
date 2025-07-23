<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CartStoreRequest;
use App\Http\Requests\V1\CartUpdateRequest;
use App\Http\Resources\V1\CartCollection;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::user()->id;

        return new CartCollection(Cart::with('product')->where('user_id', $userId)->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CartStoreRequest $request)
    {
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;

        $cart = Cart::where([
            'product_id' => $request->product_id,
            'user_id' => $data['user_id'],
        ])->first();

        if ($cart !== null) {
            $cartData['product_id'] = $request->product_id;
            $cartData['quantity'] = $request->quantity + $cart->quantity;
            $cart->update($cartData);
        } else {
            $cart = Cart::create($data);
        }

        return $cart;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CartUpdateRequest $request, string $id)
    {
        $userId = Auth::user()->id;

        $cartItem = Cart::findOrFail($id);

        $data = $request->all();
        $data['user_id'] = $userId;
        $cartItem->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Updated Successfully',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cartItem = Cart::findOrFail($id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Deleted Successfully',
        ], 200);
    }

    /**
     * Get Cart Count For a user
     */
    public function getCartCount()
    {
        $user_id = Auth::user()->id;
        $count = Cart::where('user_id', $user_id)->count();

        return response()->json([
            'status' => 'success',
            'count' => $count,
        ], 200);
    }
}
