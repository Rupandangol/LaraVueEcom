<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        $userCount = User::all()->count();
        $productCount = Product::all()->count();
        $pendingOrderCount = Order::where('status', 'pending')->count();
        $deliveredOrderCount = Order::where('status', 'delivered')->count();
        $orderDate = DB::select(
            "SELECT
            DATE_FORMAT(created_at, '%Y-%m') AS month,
            COUNT(*) AS pending_count
        FROM
            orders
        WHERE
            status = 'pending'
        GROUP BY
            month
        ORDER BY
            month;"
        );
        $orderStatusData = DB::select(
            "SELECT  status,count(status) as total FROM orders
            GROUP BY status;
             "
        );

        $top5Products = DB::select("SELECT product_id, count(product_id) as ordered_count from order_details
          group by product_id order by ordered_count desc limit 5;");

        return response()->json([
            'userCount' => $userCount,
            'productCount' => $productCount,
            'pendingOrderCount' => $pendingOrderCount,
            'deliveredOrderCount' => $deliveredOrderCount,
            'orderDate' => $orderDate,
            'orderStatusData' => $orderStatusData,
            'top5Products'=>$top5Products,
        ]);
    }
}
