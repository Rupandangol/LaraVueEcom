<?php

namespace App\Http\Controllers\V1;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\OrderStoreRequest;
use App\Http\Resources\V1\OrderCollection;
use App\Http\Resources\V1\OrderResource;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function PHPSTORM_META\map;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId=Auth::user()->id;
        $orders=Order::with('orderDetails')->where('user_id',$userId)->get();
        return new OrderCollection($orders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderStoreRequest $request)
    {
        $orderData=$this->orderDataBind($request);
        $order = Order::create($orderData);
        foreach ($request->product_id as $key => $item) {
            $orderDetailData=$this->orderDetailDataBind($order,$request,$key);
            $orderItem[] = OrderDetail::create($orderDetailData);
        }
        return response()->json([
            'status'=>'success',
            'message'=>'Order created successfully',
            'data'=>['order'=>$order,'orderDetails'=>$orderItem]
        ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order=Order::with('orderDetails')->findOrFail($id);
        return new OrderResource($order);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function orderDataBind($request) : array {
        $userId = Auth::user()->id;
        $data=[
            'user_id' => $userId,
            'total_price' => $request->total_price,
            'shipping_address' => $request->shipping_address,
            'status' => Status::PENDING,
        ];
        return $data;
    }
    public function orderDetailDataBind($order,$request,$key) : array {
        $data=[
            'order_id' => $order->id,
            'product_id' => $request->product_id[$key],
            'quantity' => $request->quantity[$key],
            'price' => $request->price[$key],
        ];
        return $data;
    }
}