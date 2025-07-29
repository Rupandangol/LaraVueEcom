<?php

namespace App\Http\Controllers\V1\Admin;

use App\Events\OrderStatusBroadcastEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\OrderStatusUpdateRequest;
use App\Http\Resources\V1\OrderCollection;
use App\Http\Resources\V1\OrderResource;
use App\Interfaces\ReportGeneratorInterface;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $array_filter = request()->only([
            'name',
            'status',
            'country',
            'zone',
            'district',
            'street',
            'zip_code',
        ]);

        $orders = new OrderCollection(Order::whereHas('user', function ($q) use ($array_filter) {
            if ($array_filter['name'] != '') {
                $q->where('name', 'LIKE', '%'.$array_filter['name'].'%');
            }
        })->when(count($array_filter) > 0, function ($q) use ($array_filter) {
            foreach ($array_filter as $column => $value) {
                if ($column != 'name') {
                    $q->where($column, 'LIKE', '%'.$value.'%');
                }
            }
        })->with(['orderDetails', 'user'])->orderBy('id', 'DESC')->get());
        foreach ($orders as &$item) {
            $total = 0;
            foreach ($item['orderDetails'] as $subItem) {
                $total += ($subItem['price'] * $subItem['quantity']);
            }
            $item['total_price'] = $total;
        }

        return response()->json([
            'status' => 'success',
            'data' => $orders,
        ], 200);
        // $orders = new OrderCollection(Order::with('orderDetails', 'user')->get());
        // foreach ($orders as &$item) {
        //     $total = 0;
        //     foreach ($item['orderDetails'] as $subItem) {
        //         $total += ($subItem['price'] * $subItem['quantity']);
        //     }
        //     $item['total_price'] = $total;
        // }
        // return response()->json([
        //     'status' => 'success',
        //     'data' => $orders
        // ], 200);
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
        return new OrderResource(Order::with('orderDetails.product', 'user')->where('id', $id)->first());
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
            'message' => 'Deleted Successfully',
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
            broadcast(new OrderStatusBroadcastEvent($order));
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Updated Successfully',
        ]);
    }

    public function report(ReportGeneratorInterface $report_generator)
    {
        $qwe = $report_generator->generate('orders', [
            'id',
            'user_id',
            'total_price',
            'status',
        ]);
        $filename = 'report_'.Carbon::now()->format('YmdHis').'.csv';

        return response()->stream($qwe, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }
}
