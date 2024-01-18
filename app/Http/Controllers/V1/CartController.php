<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CartStoreRequest;
use App\Http\Requests\V1\CartUpdateRequest;
use App\Http\Resources\V1\CartCollection;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId=Auth::user()->id;
        return new CartCollection(Cart::with('products')->where('user_id',$userId)->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CartStoreRequest $request)
    {
        $data=$request->all();
        $data['user_id']=Auth::user()->id;
        return Cart::create($data);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(CartUpdateRequest $request, string $id)
    {
        $userId=Auth::user()->id;

        $cartItem=Cart::findOrFail($id);

        $data=$request->all();
        $data['user_id']=$userId;
        $cartItem->update($data);
        return response()->json([
            'status'=>'success',
            'message'=>'Updated Successfully'
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cartItem=Cart::findOrFail($id)->delete();
        return response()->json([
            'status'=>'success',
            'message'=>'Deleted Successfully'
        ],200);
    }
}
