<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\OrderStatusUpdateRequest;
use App\Http\Resources\V1\OrderCollection;
use App\Http\Resources\V1\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = new OrderCollection(Order::with('orderDetails', 'user')->get());
        foreach ($orders as &$item) {
            $total = 0;
            foreach ($item['orderDetails'] as $subItem) {
                $total += ($subItem['price'] * $subItem['quantity']);
            }
            $item['total_price'] = $total;
        }
        return response()->json([
            'status' => 'success',
            'data' => $orders
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return new OrderResource(Order::with('orderDetails.product','user')->where('id', $id)->first());
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
        Order::findOrFail($id)->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Deleted Successfully'
        ], 200);
    }

    /**
     * Update the status of resource in storage.
     */
    public function statusUpdate(OrderStatusUpdateRequest $request, string $id)
    {
        $order = Order::findOrFail($id);
        if ($order->status != $request->status) {
            $order->update($request->all());
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Updated Successfully'
        ]);
    }
}
